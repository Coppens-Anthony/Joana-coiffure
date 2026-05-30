<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailability;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agenda')]
class extends Component {
    public string $selectedDate;
    public string $firstDay;
    public string $lastDay;
    public string $start;
    public string $end;

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
        $this->firstDay = now()->startOfMonth()->toDateString();
        $this->lastDay = now()->endOfMonth()->toDateString();
    }

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }

        $this->dispatch('refresh-calendar', events: $this->events);
    }


    #[On('data-set')]
    public function dataSet($firstDay, $lastDay)
    {
        $this->firstDay = $firstDay;
        $this->lastDay = $lastDay;

        $this->dispatch('refresh-calendar', events: $this->events);
    }

    #[On('date-selected')]
    public function selectDate($date)
    {
        $this->selectedDate = $date;

        $this->dispatch('open_modal', ['modal' => 'modals::agenda.show', 'params' => ['date' => $this->selectedDate]]);
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
            ->whereBetween('start_at', [$this->firstDay, $this->lastDay])
            ->get()
            ->map(fn($appointment) => [
                'title' => $appointment->client->name,
                'start' => $appointment->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s'),
                'end' => $appointment->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s'),
            ]);

        $unavailabilities = Unavailability::whereBetween('start_at', [$this->firstDay, $this->lastDay])
            ->get()
            ->map(function ($unavailability) {
                $sameDay = $unavailability->start_at->toDateString() === $unavailability->end_at->toDateString();
                $isPartial = $sameDay && !($unavailability->start_at->format('H:i') === '09:00' && $unavailability->end_at->format('H:i') === '18:00');

                return [
                    'title' => $isPartial ? 'Créneau off' : ($sameDay ? 'Journée off' : 'Période off'),
                    'start' => $isPartial ? $unavailability->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s') : $unavailability->start_at->toDateString(),
                    'end' => $isPartial ? $unavailability->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s') : $unavailability->end_at->clone()->addDay()->toDateString(),
                    'display' => $isPartial ? 'auto' : 'line',
                    'color' => '#B92629',
                ];
            });

        $recurring = RecurringUnavailability::all()
            ->flatMap(function ($rule) {
                return collect(CarbonPeriod::create($this->firstDay, $this->lastDay))
                    ->filter(fn($date) => in_array($date->dayOfWeek, $rule->days_of_week))
                    ->filter(fn($date) => !($rule->starts_on && $date->lt($rule->starts_on)))
                    ->map(fn($date) => [
                        'title' => 'Congés',
                        'start' => $date->toDateString(),
                        'end' => $date->copy()->addDay()->toDateString(),
                        'display' => 'background',
                        'color' => '#B92629',
                    ]);
            });

        return collect()
            ->merge($appointments)
            ->merge($unavailabilities)
            ->merge($recurring);
    }
};
?>
<div>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <div id="calendar" data-events='@json($this->events)' wire:ignore></div>
</div>
