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

    #[On('show-appointment')]
    public function showEvent($id)
    {
        $this->dispatch('open_modal', ['modal'  => 'modals::appointments.show', 'model_id' => $id]);
    }

    #[On('data-set')]
    public function dataSet($firstDay, $lastDay)
    {
        $this->firstDay = Carbon::parse($firstDay)->startOfDay()->toDateString();
        $this->lastDay = Carbon::parse($lastDay)->endOfDay()->toDateString();

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
        $appointments = Appointment::with('client:id,name')
            ->whereBetween('start_at', [$this->firstDay, $this->lastDay])
            ->get()
            ->map(fn($appointment) => [
                'id' => $appointment->id,
                'type' => 'appointment',
                'title' => $appointment->client->name,
                'start' => $appointment->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s'),
                'end' => $appointment->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s'),
            ]);

        $unavailabilities = Unavailability::where('start_at', '<=', $this->lastDay)
            ->where('end_at', '>=', $this->firstDay)
            ->get()
            ->map(function ($unavailability) {
                $sameDay = $unavailability->start_at->toDateString() === $unavailability->end_at->toDateString();
                $isPartial = $sameDay && !($unavailability->start_at->format('H:i') === '09:00' && $unavailability->end_at->format('H:i') === '18:00');

                return [
                    'id' => $unavailability->id,
                    'type' => 'unavailability',
                    'allDay' => !$isPartial,
                    'title' => $isPartial ? 'Créneau off' : ($sameDay ? 'Journée off' : 'Période off'),
                    'start' => $isPartial ? $unavailability->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s') : $unavailability->start_at->toDateString(),
                    'end' => $isPartial ? $unavailability->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i:s') : $unavailability->end_at->clone()->addDay()->toDateString(),
                    'display' => $isPartial ? 'auto' : 'background',
                    'color' => '#F9C784',
                    'classNames' => $isPartial ? [] : ['event-orange'],
                ];
            });

        $recurring = RecurringUnavailability::all()
            ->flatMap(function ($rule) {
                $isAllDay = Carbon::parse($rule->start_time)->format('H:i') === '09:00'
                    && Carbon::parse($rule->end_time)->format('H:i') === '18:00';

                return collect(CarbonPeriod::create($this->firstDay, $this->lastDay))
                    ->filter(fn($date) => in_array($date->dayOfWeek, $rule->days_of_week))
                    ->filter(fn($date) => !($rule->starts_on && $date->lt($rule->starts_on)))
                    ->map(fn($date) => [
                        'allDay' => $isAllDay,
                        'title' => 'Congé récurrent',
                        'start' => $isAllDay ? $date->toDateString() : $date->toDateString() . ' ' . $rule->start_time,
                        'end' => $isAllDay ? $date->copy()->addDay()->toDateString() : $date->toDateString() . ' ' . $rule->end_time,
                        'display' => $isAllDay ? 'background' : 'auto',
                        'color' => '#B92629',
                        'classNames' => $isAllDay ? ['event-red'] : [],
                    ]);
            });

        $normalFullDays = $unavailabilities
            ->filter(fn($event) => $event['allDay'])
            ->flatMap(function ($event) {
                $start = Carbon::parse($event['start']);
                $end = Carbon::parse($event['end'])->subDay();

                return collect(CarbonPeriod::create($start, $end))
                    ->map(fn($date) => $date->toDateString());
            })
            ->unique();

        $recurringFullDays = $recurring
            ->filter(fn($event) => $event['allDay'])
            ->pluck('start')
            ->unique();

        $filteredUnavailabilities = $unavailabilities->filter(
            fn($event) => $event['allDay'] || !$normalFullDays->contains(Carbon::parse($event['start'])->toDateString())
        );

        $filteredRecurring = $recurring->filter(function ($event) use ($normalFullDays, $recurringFullDays) {
            $date = $event['allDay'] ? $event['start'] : Carbon::parse($event['start'])->toDateString();

            if ($normalFullDays->contains($date)) return false;
            if (!$event['allDay'] && $recurringFullDays->contains($date)) return false;

            return true;
        });

        return collect()
            ->merge($appointments)
            ->merge($filteredUnavailabilities)
            ->merge($filteredRecurring);
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
    <livewire:admin.agenda.legend/>
    <div id="calendar" data-events='@json($this->events)' wire:ignore></div>
</div>
