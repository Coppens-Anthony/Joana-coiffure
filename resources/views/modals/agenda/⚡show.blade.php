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
                'start_at' => $unavailability->start_at,
                'end_at' => $unavailability->end_at,
                'model' => $unavailability,
            ]);

        $date = Carbon::parse($this->selectedDate);

        $recurringRules = $this->recurringRules
            ->map(fn($rule) => [
                'id' => 'recurring-' . $rule->id . '-' . $date->toDateString(),
                'type' => 'recurring_unavailability',
                'start_at' => $date->copy()->setTime('09', '00'),
                'end_at' => $date->copy()->setTime('18', '00'),
                'model' => $rule,
            ]);

        return collect()
            ->merge($appointments)
            ->merge($unavailabilities)
            ->merge($recurringRules)
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
        return $this->selectedEvents->contains(fn($event) =>
            $event['type'] === 'unavailability' &&
            $event['start_at']->format('H:i') === '09:00' &&
            $event['end_at']->format('H:i') === '18:00'
        );
    }
};
?>

<livewire:admin.modal :modal_title="Carbon::parse($this->selectedDate)->translatedFormat('d F Y')">
    <div class="max-h-95 flex flex-col">
        <div class="flex-1 overflow-y-scroll scroll no-scrollbar min-h-0">
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
                <x-global.linkButton.button class="w-full" type="button" title="Ajouter un rendez-vous"
                                            wire:click="createAppointment">
                    Ajouter un rendez-vous
                </x-global.linkButton.button>

                <x-global.linkButton.button class="w-full" :isSecondary="true" type="button"
                                            wire:click="createUnavailability"
                                            title="Définir une période off">
                    Définir une période off
                </x-global.linkButton.button>
            </div>
        @else
            <x-global.linkButton.button
                type="button"
                title="Fermer la modale"
                class="ml-auto block mt-8"
                :isSecondary="true"
                wire:click="dispatch('close_modal')">
                Annuler
            </x-global.linkButton.button>
        @endif
    </div>
</livewire:admin.modal>
