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

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }

        $this->dispatch('refresh-calendar', events: $this->appointments);
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
<div class="flex flex-col lg:flex-row gap-8 lg:max-h-200">
    @if(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <div class="flex-1" id="calendar" data-events='@json($this->appointments)' wire:ignore></div>
    <aside class="lg:w-1/3 p-8 pt-0 shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl scroll-auto overflow-y-scroll scroll no-scrollbar">
        <h3 class="text-2xl text-center mb-8  bg-white py-8 sticky top-0">
            {{ Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
        </h3>
        @if($this->selectedAppointments->count() > 0)
            <ol class="flex flex-col gap-4">
                @foreach($this->selectedAppointments as $appointment)
                    <livewire:admin.appointment.item_line :isDashboard="false" :appointment="$appointment"/>
                @endforeach
            </ol>
        @else
            <p>Pas de rendez-vous ce jour-ci.</p>
        @endif
    </aside>
</div>
