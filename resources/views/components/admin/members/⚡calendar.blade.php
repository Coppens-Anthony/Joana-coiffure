<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailability;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public User $user;
    public string $calendar_name;
    public string $selectedDate;
    public string $firstDay;
    public string $lastDay;
    public string $start;
    public string $end;

    public function mount(User $user)
    {
        $this->user = $user;
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
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.show', 'model_id' => $id]);
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

        $this->dispatch('open_modal', ['modal' => 'modals::agenda.show', 'params' => ['date' => $this->selectedDate, 'userId' => $this->user->id]]);
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
        $user = User::where('isAdmin', true)->first();

        $appointments = Appointment::with('client:id,name')
            ->when(!$this->user->isAdmin, function ($query) {
                $query->where('user_id', $this->user->id);
            })
            ->whereBetween('start_at', [$this->firstDay, $this->lastDay])
            ->get()
            ->map(fn($appointment) => [
                'id' => $appointment->id,
                'type' => 'appointment',
                'title' => $appointment->client->name,
                'start' => $appointment->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i'),
                'end' => $appointment->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i'),
                'color' => $appointment->user->color
            ]);


        $unavailabilities = Unavailability::where('start_at', '<=', $this->lastDay)
            ->whereIn('user_id', [$this->user->id, $user->id])
            ->where('end_at', '>=', $this->firstDay)
            ->get()
            ->map(function ($unavailability) {
                $sameDay = $unavailability->start_at->toDateString() === $unavailability->end_at->toDateString();
                $isPartial = $sameDay && !($unavailability->start_at->format('H:i') === config('app.hours.hour_start') && $unavailability->end_at->format('H:i') === config('app.hours.hour_end'));

                return [
                    'id' => $unavailability->id,
                    'type' => 'unavailability',
                    'allDay' => !$isPartial,
                    'title' => $isPartial ? 'Créneau indisponible' : ($sameDay ? 'Journée indisponible' : 'Période indisponible'),
                    'start' => $isPartial ? $unavailability->start_at->timezone('Europe/Brussels')->format('Y-m-d H:i') : $unavailability->start_at->toDateString(),
                    'end' => $isPartial ? $unavailability->end_at->timezone('Europe/Brussels')->format('Y-m-d H:i') : $unavailability->end_at->clone()->addDay()->toDateString(),
                    'display' => $isPartial ? 'auto' : 'background',
                    'color' => $isPartial ? '#AC2022' : '#C8C8C8FF',
                    'classNames' => $isPartial ? [] : ['event-orange'],
                ];
            });

        $recurring = RecurringUnavailability::whereIn('user_id', [$this->user->id, $user->id])
            ->get()
            ->flatMap(function ($rule) {
                $isAllDay = Carbon::parse($rule->start_time)->format('H:i') === config('app.hours.hour_start')
                    && Carbon::parse($rule->end_time)->format('H:i') === config('app.hours.hour_end');

                if ($isAllDay) {
                    return collect(CarbonPeriod::create($this->firstDay, $this->lastDay))
                        ->filter(fn($date) => in_array($date->dayOfWeek, $rule->days_of_week))
                        ->filter(fn($date) => !($rule->starts_on && $date->lt($rule->starts_on)))
                        ->map(fn($date) => [
                            'allDay' => $isAllDay,
                            'title' => 'Congé récurrent',
                            'start' => $isAllDay ? $date->toDateString() : $date->toDateString() . ' ' . $rule->start_time,
                            'end' => $isAllDay ? $date->copy()->addDay()->toDateString() : $date->toDateString() . ' ' . $rule->end_time,
                            'display' => $isAllDay ? 'background' : 'auto',
                            'color' => '#C8C8C8FF',
                            'classNames' => $isAllDay ? ['event-red'] : [],
                        ]);
                } else {
                    return collect();
                }
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
    <div id="{{ $this->calendar_name }}" data-events='@json($this->events)' wire:ignore></div>
</div>
