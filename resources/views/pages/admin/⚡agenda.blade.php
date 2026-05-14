<?php

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agenda')]
class extends Component {
    public string $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
    }

    #[On('date-selected')]
    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    #[Computed]
    public function selectedAppointments()
    {
        return Appointment::with('client')->whereDate('start_at', $this->selectedDate)->orderBy('start_at')->get();
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::with('client')
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
    }
};
?>
<div class="flex flex-col md:flex-row gap-8 md:max-h-200">
    <div class="flex-1" id="calendar" data-events='@json($this->appointments)' wire:ignore></div>
    <aside class="md:w-1/4 p-8 shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
        <h3 class="text-2xl w-fit mx-auto mb-8">
            {{ Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
        </h3>
        @if($this->selectedAppointments->count() > 0)
            <ol>
                @foreach($this->selectedAppointments as $appointment)
                    <li>
                        {{ $appointment->client->name }}
                    </li>
                @endforeach
            </ol>
        @else
            <p>Pas de rendez-vous ce jour-ci.</p>
        @endif
    </aside>
</div>
