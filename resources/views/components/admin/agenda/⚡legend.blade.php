<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="w-fit mx-auto mb-8">
    <ul class="flex flex-col sm:flex-row lg:flex gap-4">
        <li class="flex gap-2 items-center">
            <span class="w-4 h-4 rounded-full block bg-[#3788d8]"></span>Rendez-vous
        </li>
        <li class="flex gap-2 items-center">
            <span class="w-4 h-4 rounded-full block bg-error"></span>Créneau indisponible
        </li>
        <li class="flex gap-2 items-center">
            <span class="w-4 h-4 rounded-full block bg-dayOff"></span>Journée indisponible
        </li>
    </ul>
</div>
