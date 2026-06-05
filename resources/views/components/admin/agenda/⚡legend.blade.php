<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <section x-data="{expanded: false}" class="mb-4 md:mb-8 bg-white shadow-[0_0_10px_rgba(0,0,0,0.25)] w-full p-8 rounded-2xl">
        <div class="flex items-center justify-between cursor-pointer"
             tabindex="0"
             @click="expanded = !expanded"
             @keydown.enter="expanded = !expanded"
             @keydown.space.prevent="expanded = !expanded">
            <h2 class="text-2xl">Légende</h2>
            <img src="{{ asset('assets/svg/chevron.svg') }}" alt="Étendre le menu" class="transition-transform duration-200"
                 :class="{'rotate-180': expanded}">
        </div>
        <div x-show="expanded" class="flex flex-col gap-4 mt-4">
            <ul class="flex flex-col sm:flex-row lg:flex gap-4">
                <li class="flex gap-2 items-center">
                    <span class="w-4 h-4 rounded-full block bg-[#3788d8]"></span>Rendez-vous
                </li>
                <li class="flex gap-2 items-center">
                    <span class="w-4 h-4 rounded-full block bg-unavailability"></span>Période off
                </li>
                <li class="flex gap-2 items-center">
                    <span class="w-4 h-4 rounded-full block bg-error"></span>Congés récurrents
                </li>
            </ul>
            <ul class="flex flex-col gap-2">
                <li>
                    Au clic d'un jour, appraîtra toutes les informations liées à ce jour
                </li>
                <li>
                    Pour ajouter une période off de plusieurs jours, cliquez et glissez sur les jours en question
                    <small>(restez appuyer en survolant les jours)</small>
                </li>
            </ul>
        </div>
    </section>
</div>
