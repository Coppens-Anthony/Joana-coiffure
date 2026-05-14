<?php

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')]
class extends Component {

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::with('client')->whereDate('start_at', today())->orderBy('start_at')->get();
    }

    public function show(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.show', 'model_id' => $id]);
    }

    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.delete', 'model_id' => $id]);
    }
};
?>

<div>
    @if(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <section>
        <h2 class="text-2xl">Les rendez-vous de la journée</h2>
        @if($this->appointments->count() <= 0)
            <p class="mt-8">Il n'y a pas de rendez-vous aujourd'hui.</p>
        @endif
        <ol class="mt-6 flex flex-col gap-8">
            @foreach($this->appointments as $appointment)
                <li class="pb-8 border-b border-b-black flex flex-col sm:flex-row sm:justify-between gap-4 sm:gap-8 sm:items-center">
                    <div class="flex flex-col md:items-center text-2xl">
                        <p>{{ Carbon::parse($appointment->start_at)->format('H\hi') }}</p>
                        <p>{{ Carbon::parse($appointment->end_at)->format('H\hi') }}</p>
                    </div>
                    <div class="bg-primary w-full p-4 rounded-2xl flex flex-col sm:flex-row sm:justify-between sm:gap-8 sm:items-center">
                        <div class="flex-1">
                            <p class="flex items-center gap-2">
                                {!! $appointment->services->pluck('name')->implode('
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>') !!}
                            </p>
                            <p class="flex items-center gap-2">{{ $appointment->client->name }}
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>
                                {{$appointment->services->sum('price')}}€
                            </p>
                        </div>
                        <div class="flex gap-4 items-center">
                            <button class="cursor-pointer" wire:click="show({{ $appointment->id }})">
                                <img src="{{ asset('assets/svg/eye.svg') }}" alt="Voir le rendez-vous en détail"
                                     class="w-5 h-5 lg:w-8 lg:h-8">
                            </button>
                            <button class="cursor-pointer" wire:click="delete({{ $appointment->id }})">
                                <img src="{{ asset('assets/svg/delete.svg') }}" alt="Annuler le rendez-vous"
                                     class="w-4 h-4 lg:w-6 lg:h-6">
                            </button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
</div>
