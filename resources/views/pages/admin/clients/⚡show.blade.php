<?php

use App\Models\Client;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Fiche du client')]
class extends Component {
    public Client $client;

    public function mount(Client $client): void
    {
        $this->client = $client->load(['appointments.services']);
    }
};
?>

<div>
    <section>
        <h2 class="text-2xl">{{ $client->name }}</h2>
        <ul class="flex flex-col md:flex-row gap-2 md:items-center mt-2">
            <li>{{ $client->email }}</li>
            <span class="hidden md:inline-block w-1 h-1 rounded-full bg-black"></span>
            <li>{{ $client->telephone }}</li>
            <span class="hidden md:inline-block w-1 h-1 rounded-full bg-black"></span>
            <li>{{ $client->appointments->count() }} rendez-vous</li>
        </ul>
    </section>
    <section class="mt-8 mb-16 bg-tertiary p-6 rounded-2xl" x-data="{expanded: false}">
        <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
            <h2 class="text-2xl">Notes personnelles</h2>
            <img src="{{ asset('assets/svg/chevron.svg') }}" alt="" class="transition-transform duration-200"
                 :class="{'rotate-180': expanded}">
        </div>
        <div x-show="expanded" class="mt-4">
            <ol class="flex flex-col gap-2">
                <li class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:items-center">
                    <p class="w-3/4">10/04/2026 : Sophie travaille dans l’immobilier.</p>
                    <div class="flex gap-2 w-fit">
                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la note">
                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Supprimer la note">
                    </div>
                </li>
                <li class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:items-center">
                    <p class="w-3/4">10/04/2026 : Sophie travaille dans l’immobilier.</p>
                    <div class="flex gap-2 w-fit">
                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la note">
                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Supprimer la note">
                    </div>
                </li>
                <li class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:items-center">
                    <p class="w-3/4">10/04/2026 : Sophie travaille dans l’immobilier.</p>
                    <div class="flex gap-2 w-fit">
                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la note">
                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Supprimer la note">
                    </div>
                </li>

            </ol>
            <x-global.linkbutton.button_link class="mt-4" title="Ajouter une note">
                + Ajouter une note
            </x-global.linkbutton.button_link>
        </div>
    </section>
    <section>
        <h2 class="text-2xl mb-4">Historique des rendez-vous</h2>
        <x-global.table :titles="['Date', 'Prestation(s)', 'Durée', 'Prix', 'Informations supplémentaires']">
            @if($client->appointments->count() > 0)
                @foreach($client->appointments as $appointment)
                    <tr class="table__tr">
                        <td class="text_td">
                            <span class="title_td">Date</span>
                            {{ $appointment->formatDate('updated_at') }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Prestation(s)</span>
                            {!! $appointment->services->map(fn($service) => $service->trashed()
                                ? $service->name . ' <small class="text-[.75rem]">(Supprimé)</small>'
                                : $service->name
                            )->implode(' / ') !!}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Durée</span>
                            {{ $appointment->durationFormat($appointment->services->sum('duration')) }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Prix</span>
                            {{ $appointment->services->sum('price') }}€
                        </td>
                        <td class="text_td">
                            <span class="title_td">Informations supplémentaires</span>
                            <small class="text-[.75rem] italic">{{ $appointment->message ?? '/' }}</small>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="py-2" colspan="5">Aucun résultat</td>
                </tr>
            @endif
        </x-global.table>
    </section>
</div>
