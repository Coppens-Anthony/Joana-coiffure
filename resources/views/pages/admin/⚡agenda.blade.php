<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailabilities;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agenda')]
class extends Component {
    public string $selectedDate;
    public string $start;
    public string $end;

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
    }

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }

        $this->dispatch('refresh-calendar', events: $this->events);
    }

    #[On('date-selected')]
    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    #[On('unavailabilities-selected')]
    public function unavailabilitiesSelected($start, $end)
    {
        $this->start = $start;
        $this->end = $end;

        $this->dispatch('open_modal', ['modal' => 'modals::unavailabilities.create', 'params' => ['start_date' => $this->start, 'end_date' => $this->end]]);
    }

    #[Computed]
    public function events()
    {
        $appointments = Appointment::with('client')
            ->get()
            ->map(fn($appointment) => [
                'title' => $appointment->client->name,
                'start' => $appointment->start_at
                    ->timezone('Europe/Brussels')
                    ->format('Y-m-d H:i:s'),
                'end' => $appointment->end_at
                    ->timezone('Europe/Brussels')
                    ->format('Y-m-d H:i:s'),
            ]);

        $unavailabilities = Unavailabilities::all()->map(function ($unavailability) {
            $sameDay = $unavailability->start_at->toDateString() === $unavailability->end_at->toDateString();
            $isPartial = $sameDay && !($unavailability->start_at->format('H:i') === '09:00' && $unavailability->end_at->format('H:i') === '18:00');

            return [
                'title' => $isPartial ? 'Créneau off' : ($sameDay ? 'Journée off' : 'Période off'),
                'start' => $isPartial
                    ? $unavailability->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s')
                    : $unavailability->start_at->toDateString(),
                'end' => $isPartial
                    ? $unavailability->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s')
                    : $unavailability->end_at->clone()->addDay()->toDateString(),
                'display' => $isPartial ? 'auto' : 'line',
                'color' => '#B92629',
            ];
        });
        $recurring = $this->getRecurringUnavailabilityEvents();

        return $appointments
            ->merge($unavailabilities)
            ->merge($recurring);
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

        $unavailabilities = Unavailabilities::whereDate('start_at', '<=', $this->selectedDate)
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

        $recurringRules = RecurringUnavailability::where('is_active', true)->get()
            ->filter(fn($rule) => in_array($date->dayOfWeek, $rule->days_of_week))
            ->map(fn($rule) => [
                'id' => 'recurring-' . $rule->id . '-' . $date->toDateString(),
                'type' => 'recurring_unavailability',
                'start_at' => $date->copy()->setTimeFromTimeString($rule->start_time),
                'end_at' => $date->copy()->setTimeFromTimeString($rule->end_time),
                'model' => $rule,
            ]);

        return collect()
            ->merge($appointments)
            ->merge($unavailabilities)
            ->merge($recurringRules)
            ->sortBy('start_at')
            ->values();
    }

    private function getRecurringUnavailabilityEvents()
    {
        $rules = RecurringUnavailability::where('is_active', true)->get();

        $events = collect();

        $start = now()->startOfMonth()->subMonth();
        $end = now()->endOfMonth()->addYear();

        $period = CarbonPeriod::create($start, $end);

        foreach ($rules as $rule) {

            foreach ($period as $date) {

                if (!in_array($date->dayOfWeek, $rule->days_of_week)) {
                    continue;
                }

                if ($rule->starts_on && $date->lt($rule->starts_on)) continue;
                if ($rule->ends_on && $date->gt($rule->ends_on)) continue;

                $startAt = $date->copy()->setTimeFromTimeString($rule->start_time);
                $endAt = $date->copy()->setTimeFromTimeString($rule->end_time);

                $events->push([
                    'title' => 'Congés',
                    'start' => $startAt->toDateString(),
                    'end' => $endAt->copy()->addDay()->toDateString(),
                    'display' => 'line',
                    'color' => '#B92629',
                ]);
            }
        }

        return $events;
    }

    #[Computed]
    public function isRecurringBlocked()
    {
        $date = Carbon::parse($this->selectedDate);

        return RecurringUnavailability::where('is_active', true)
            ->get()
            ->contains(fn($rule) => in_array($date->dayOfWeek, $rule->days_of_week));
    }
};
?>
<div class="flex flex-col lg:flex-row gap-8 lg:max-h-200">
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <div class="flex-1" id="calendar" data-events='@json($this->events)' wire:ignore></div>
    <aside class="xl:w-1/3 flex flex-col p-8 pt-0 shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl max-h-200">

        <h3 class="text-2xl text-center bg-white py-8 sticky top-0 z-10">
            {{ Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
        </h3>

        <div class="flex-1 overflow-y-scroll scroll no-scrollbar">
            @if($this->selectedEvents->count() > 0)
                <ol class="flex flex-col gap-4">
                    @foreach($this->selectedEvents as $event)
                        @if($event['type'] === 'appointment')
                            <livewire:admin.appointment.item_line
                                :isDashboard="false"
                                :appointment="$event['model']"
                                :key="$event['model']->id . '-' . $selectedDate"
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

        @php
            $isFullDayOff = $this->selectedEvents->contains(fn($event) =>
                $event['type'] === 'unavailability' &&
                $event['start_at']->format('H:i') === '09:00' &&
                $event['end_at']->format('H:i') === '18:00'
            );
        @endphp

        @if(Carbon::parse($this->selectedDate)->startOfDay() >= now()->startOfDay() && !$isFullDayOff && !$this->isRecurringBlocked)
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
        @endif

    </aside>
</div>
