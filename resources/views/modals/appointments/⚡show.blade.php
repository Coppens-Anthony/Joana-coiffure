<?php

use App\Models\Appointment;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public Appointment $appointment;

    public function mount(string $model_id)
    {
        $this->appointment = Appointment::findOrFail($model_id);
    }
};
?>

<livewire:admin.modal
    modal_title="Rendez-vous du {{ $appointment->formatDate('start_at') . ' de ' . Carbon::parse($appointment->start_at)->format('H\hi') . ' à ' . Carbon::parse($appointment->end_at)->format('H\hi')}}">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <x-global.link-button.link class="text-2xl w-fit mb-2" :route="route('clients.⚡show', $appointment->client->id)"
                                       title="Vers la fiche de {{ $appointment->client->name }}">{{ $appointment->client->name }}</x-global.link-button.link>
            <p class="flex gap-2 items-center">
                {{ $appointment->client->email }}
                <span class="hidden md:inline-block w-1.5 h-1.5 rounded-full bg-black"></span>
                {{ $appointment->client->telephone }}
            </p>
            <small class="text-[.75]">{{ $appointment->message }}</small>
        </div>
        <div class="flex flex-col gap-4">
            <p class="text-2xl">Prestation(s)</p>
            <p class="flex items-center gap-2">
                {!! $appointment->services->pluck('name')->implode('
             <span class="hidden md:inline-block w-1.5 h-1.5 rounded-full bg-black"></span>') !!}
            </p>
        </div>
        <div class="flex flex-col gap-4">
            <p class="text-2xl">Résumé du rendez-vous</p>
            <p class="flex items-center gap-2">
                +/- {{ $appointment->durationFormat($appointment->services->sum('duration')) }} de prestation
                <span class="hidden md:inline-block w-1.5 h-1.5 rounded-full bg-black"></span>
                {{ $appointment->services->sum('price') }}€
            </p>
        </div>
    </div>
    <x-global.link-button.button class="ml-auto block mt-4" title="Fermer la modale" :isSecondary="true"
                                 wire:click="dispatch('close_modal')">Fermer
    </x-global.link-button.button>
</livewire:admin.modal>
