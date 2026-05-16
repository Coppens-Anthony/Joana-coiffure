<?php

use App\Models\Appointment;
use App\Models\Unavailabilities;
use Carbon\Carbon;
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

        $this->dispatch('refresh-calendar', events: $this->appointments);
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

    public function unavailability()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::unavailabilities.create', 'params' => ['date' => $this->selectedDate]]);
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
    <div class="flex-1" id="calendar" data-events='@json($this->appointments)' wire:ignore></div>
    <aside class="lg:w-1/3 flex flex-col p-8 pt-0 shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl max-h-200">

        <h3 class="text-2xl text-center bg-white py-8 sticky top-0 z-10">
            {{ Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
        </h3>

        <div class="flex-1 overflow-y-scroll scroll no-scrollbar">
            @if($this->selectedAppointments->count() > 0)
                <ol class="flex flex-col gap-4">
                    @foreach($this->selectedAppointments as $appointment)
                        <livewire:admin.appointment.item_line
                            :isDashboard="false"
                            :appointment="$appointment"
                            :key="$appointment->id . '-' . $selectedDate"
                        />
                    @endforeach
                </ol>
            @else
                <p>Pas de rendez-vous ce jour-ci.</p>
            @endif
        </div>

        @if(Carbon::parse($this->selectedDate)->startOfDay() >= now()->startOfDay())
            <div class="bg-white pt-8 sticky bottom-0 flex flex-col gap-4 ">
                <x-global.linkButton.button class="w-full" type="button" title="Ajouter un rendez-vous">
                    Ajouter un rendez-vous
                </x-global.linkButton.button>

                <x-global.linkButton.button class="w-full" :isSecondary="true" type="button" wire:click="unavailability"
                                            title="Définir une période off">
                    Définir une période off
                </x-global.linkButton.button>
            </div>
        @endif
    </aside>
</div>
