<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailability;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public string $selectedDate;

    public function mount($params)
    {
        $this->selectedDate = $params['date'];
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
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.create', 'params' => ['date' => $this->selectedDate]]);
    }

    #[Computed]
    public function recurringRules()
    {
        $date = Carbon::parse($this->selectedDate);

        return RecurringUnavailability::whereDate('starts_on', '<=', $date)
            ->get()
            ->filter(fn($rule) => in_array($date->dayOfWeek, $rule->days_of_week));
    }

    #[Computed]
    public function selectedEvents()
    {
        $date = Carbon::parse($this->selectedDate);

        $appointments = Appointment::with('client')
            ->whereDate('start_at', $this->selectedDate)
            ->orderBy('start_at')
            ->get()
            ->map(fn($appointment) => [
                'type' => 'appointment',
                'start_at' => $appointment->start_at,
                'end_at' => $appointment->end_at,
                'model' => $appointment,
            ]);

        $unavailabilities = Unavailability::whereDate('start_at', '<=', $this->selectedDate)
            ->whereDate('end_at', '>=', $this->selectedDate)
            ->orderBy('start_at')
            ->get()
            ->map(fn($unavailability) => [
                'id' => $unavailability->id,
                'type' => 'unavailability',
                'allDay' => $unavailability->start_at->format('H:i') === '09:00' && $unavailability->end_at->format('H:i') === '18:00',
                'start_at' => $unavailability->start_at,
                'end_at' => $unavailability->end_at,
                'model' => $unavailability,
            ]);

        $recurringRules = $this->recurringRules
            ->map(fn($rule) => [
                'id' => 'recurring-' . $rule->id . '-' . $date->toDateString(),
                'type' => 'recurring_unavailability',
                'allDay' => $rule->start_time === '09:00' && $rule->end_time === '18:00',
                'start_at' => $date->copy()->setTimeFromTimeString($rule->start_time),
                'end_at' => $date->copy()->setTimeFromTimeString($rule->end_time),
                'model' => $rule,
            ]);

        $hasNormalFullDay = $unavailabilities->contains(fn($e) => $e['allDay']);
        $hasRecurringFullDay = $recurringRules->contains(fn($e) => $e['allDay']);

        if ($hasNormalFullDay) {
            $offEvents = $unavailabilities->filter(fn($e) => $e['allDay']);
        } elseif ($hasRecurringFullDay) {
            $offEvents = $recurringRules->filter(fn($e) => $e['allDay']);
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
        return $this->recurringRules->contains(
            fn($rule) => Carbon::parse($rule->start_time)->format('H:i') === '09:00'
                && Carbon::parse($rule->end_time)->format('H:i') === '18:00'
        );
    }

    #[Computed]
    public function isFullDayOff(): bool
    {
        return $this->selectedEvents->contains(fn($event) => $event['type'] === 'unavailability' &&
            $event['start_at']->format('H:i') === '09:00' &&
            $event['end_at']->format('H:i') === '18:00'
        );
    }
};
?>

<livewire:admin.modal :modal_title="Carbon::parse($this->selectedDate)->translatedFormat('d F Y')">
    <div class="max-h-120 flex flex-col">
        <div class="flex-1 overflow-y-scroll scroll no-scrollbar">
            @if($this->selectedEvents->count() > 0)
                <ol class="flex flex-col gap-4">
                    @foreach($this->selectedEvents as $event)
                        @if($event['type'] === 'appointment')
                            <livewire:admin.appointment.item_line
                                :isDashboard="false"
                                :appointment="$event['model']"
                                :key="$event['model']->id . '-' . $this->selectedDate"
                            />
                        @else
                            <livewire:admin.off :unavailability="$event" :key="'unav-' . $event['id']"/>
                        @endif
                    @endforeach
                </ol>
            @else
                <p>Aucune activité ce jour-ci.</p>
            @endif
        </div>

        @if(Carbon::parse($this->selectedDate)->startOfDay() >= now()->startOfDay() && !$this->isFullDayOff && !$this->isRecurringBlocked)
            <div class="bg-white pt-8 sticky bottom-0 flex flex-col gap-4">
                <x-global.link-button.button class="w-full" type="button" title="Ajouter un rendez-vous"
                                            wire:click="createAppointment">
                    Ajouter un rendez-vous
                </x-global.link-button.button>

                <x-global.link-button.button class="w-full" :isSecondary="true" type="button"
                                            wire:click="createUnavailability"
                                            title="Définir une période off">
                    Définir une période off
                </x-global.link-button.button>
            </div>
        @else
            <x-global.link-button.button
                type="button"
                title="Fermer la modale"
                class="ml-auto block mt-8"
                :isSecondary="true"
                wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
        @endif
    </div>
</livewire:admin.modal>
