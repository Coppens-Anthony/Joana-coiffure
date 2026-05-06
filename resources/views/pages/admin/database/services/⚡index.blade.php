<?php

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Données')]
class extends Component {

};
?>

<div>
    <div class="mb-8 flex gap-8">
        <x-global.linkButton.link class="font-bold" route="{{ route('database.services') }}" wire:navigate title="Vers les prestations" :isActive="request()->routeIs('database.services')">Prestations</x-global.linkButton.link>
        <x-global.linkButton.link class="font-bold" route="{{ route('database.photos') }}" wire:navigate title="Vers les services" :isActive="request()->routeIs('database.photos')">Photos</x-global.linkButton.link>
    </div>
    <livewire:admin.database.services/>
</div>
