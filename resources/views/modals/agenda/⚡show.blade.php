<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailability;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public User $user;
    public string $selectedDate;
    public bool $isReadOnly;

    public function mount($params)
    {
        $this->user = User::findOrFail($params['userId']);
        $this->selectedDate = $params['date'];
        $this->isReadOnly = $this->user->id != auth()->id();
    }

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false, bool $closeModal = true)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }

        if ($closeModal) {
            $this->dispatch('close_modal');
        }
    }

    public function createUnavailability()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::unavailabilities.create', 'params' => ['date' => $this->selectedDate]]);
    }

    public function createAppointment()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.create_edit', 'params' => ['date' => $this->selectedDate]]);
    }

    #[Computed]
    public function recurringRules()
    {
        $date = Carbon::parse($this->selectedDate);
        $user = User::where('isAdmin', true)->first();

        return RecurringUnavailability::whereDate('starts_on', '<=', $date)
            ->whereIn('user_id', [$this->user->id, $user->id])
            ->get()
            ->filter(fn($rule) => in_array($date->dayOfWeek, $rule->days_of_week))
            ->filter(fn($rule) => Carbon::parse($rule->start_time)->format('H:i') === config('app.hours.hour_start')
                && Carbon::parse($rule->end_time)->format('H:i') === config('app.hours.hour_end'));
    }

    #[Computed]
    public function selectedEvents()
    {
        $date = Carbon::parse($this->selectedDate);

        $appointments = Appointment::with('client:id,name')
            ->when(!$this->user->isAdmin, function ($query) {
                $query->where('user_id', $this->user->id);
            })
            ->whereDate('start_at', $this->selectedDate)
            ->orderBy('start_at')
            ->get()
            ->map(fn($appointment) => [
                'type' => 'appointment',
                'start_at' => $appointment->start_at,
                'end_at' => $appointment->end_at,
                'model' => $appointment,
            ]);

        $user = User::where('isAdmin', true)->first();

        $unavailabilities = Unavailability::whereDate('start_at', '<=', $this->selectedDate)
            ->whereDate('end_at', '>=', $this->selectedDate)
            ->whereIn('user_id', [$this->user->id, $user->id])
            ->orderBy('start_at')
            ->get()
            ->map(fn($unavailability) => [
                'id' => $unavailability->id,
                'type' => 'unavailability',
                'allDay' => $unavailability->start_at->format('H:i') === config('app.hours.hour_start') && $unavailability->end_at->format('H:i') === config('app.hours.hour_end'),
                'start_at' => $unavailability->start_at,
                'end_at' => $unavailability->end_at,
                'model' => $unavailability,
            ]);

        $recurringRules = $this->recurringRules
            ->map(fn($rule) => [
                'id' => 'recurring-' . $rule->id . '-' . $date->toDateString(),
                'type' => 'recurring_unavailability',
                'allDay' => $rule->start_time === config('app.hours.hour_start') && $rule->end_time === config('app.hours.hour_end'),
                'start_at' => $date->copy()->setTimeFromTimeString($rule->start_time),
                'end_at' => $date->copy()->setTimeFromTimeString($rule->end_time),
                'model' => $rule,
            ]);

        $hasNormalFullDay = $unavailabilities->contains(fn($event) => $event['allDay']);
        $hasRecurringFullDay = $recurringRules->contains(fn($event) => $event['allDay']);

        if ($hasNormalFullDay) {
            $offEvents = $unavailabilities->filter(fn($event) => $event['allDay']);
        } elseif ($hasRecurringFullDay) {
            $offEvents = $recurringRules->filter(fn($event) => $event['allDay']);
        } else {
            $offEvents = collect()
                ->merge($unavailabilities)
                ->merge($recurringRules)
                ->sortBy('start_at')
                ->values();
        }

        return collect()
            ->merge($appointments)
            ->merge($offEvents)
            ->sortBy('start_at')
            ->values();
    }

    #[Computed]
    public function isRecurringBlocked(): bool
    {
        return $this->recurringRules->isNotEmpty();
    }

    #[Computed]
    public function isFullDayOff(): bool
    {
        return $this->selectedEvents->contains(fn($event) => $event['type'] === 'unavailability' &&
            $event['start_at']->format('H:i') === config('app.hours.hour_start') &&
            $event['end_at']->format('H:i') === config('app.hours.hour_end')
        );
    }
};
?>

<livewire:admin.modal :modal_title="Carbon::parse($this->selectedDate)->translatedFormat('d F Y')">
    <div class="max-h-[70vh] flex flex-col">
        <div class="flex-1 overflow-y-scroll scroll no-scrollbar min-h-0">
            @if($this->selectedEvents->isEmpty())
                <p>Aucune activité ce jour-ci</p>
            @else
                <ol class="flex flex-col gap-8">
                    @foreach($this->selectedEvents as $event)
                        @if($event['type'] === 'appointment')
                            <livewire:admin.appointment.item_line
                                :isDashboard="false"
                                :appointment="$event['model']"
                                :key="$event['model']->id . '-' . $this->selectedDate"
                                :isReadOnly="$this->isReadOnly"
                            />
                        @elseif($event['type'] === 'unavailability')
                            <livewire:admin.off
                                :unavailability="$event"
                                :key="'unav-' . $event['id']"
                                :isReadOnly="$this->isReadOnly"
                            />
                        @else
                            <li>Journée indisponible</li>
                        @endif
                    @endforeach
                </ol>
            @endif
        </div>

        @if(!$this->isReadOnly && Carbon::parse($this->selectedDate)->startOfDay() >= now()->startOfDay() && !$this->isFullDayOff && !$this->isRecurringBlocked)
            <div class="bg-white pt-8 flex flex-col gap-4">
                <x-global.link-button.button class="w-full" type="button" title="Ajouter un rendez-vous"
                                             wire:click="createAppointment">
                    Ajouter un rendez-vous
                </x-global.link-button.button>

                <x-global.link-button.button class="w-full" :isSecondary="true" type="button"
                                             wire:click="createUnavailability"
                                             title="Définir une période off">
                    Définir une période d'indisponibilité
                </x-global.link-button.button>
            </div>
        @else
            <x-global.link-button.button
                type="button"
                title="Fermer la modale"
                class="ml-auto block mt-8"
                wire:click="dispatch('close_modal')">
                Fermer
            </x-global.link-button.button>
        @endif
    </div>
</livewire:admin.modal>
