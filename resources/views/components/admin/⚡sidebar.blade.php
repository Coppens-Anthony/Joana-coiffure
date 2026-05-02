<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }
};
?>

<div>
    <aside
        class="p-4 flex flex-col justify-between h-screen w-fit shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-br-2xl rounded-tr-2xl">
        <h2 class="sr-only">Barre latérale</h2>
        <div>
            <div class="relative w-fit h-fit mb-4">
                <a href="{{ route('dashboard') }}" title="Revenir au dashbaord"
                   class="w-full h-full absolute cursor-pointer"></a>
                <img src="" alt="LOGO">
            </div>
            <nav>
                <h3 class="sr-only">Navigation</h3>

                <ul class="flex flex-col gap-8 pt-4 border-t border-black">
                    <li>
                        <x-global.icon_link icon_path="assets/svg/dashboard.svg" :route="route('dashboard')"
                                            title="Vers le dashboard" :isActive="request()->routeIs('dashboard')">
                            Dashboard
                        </x-global.icon_link>
                    </li>
                    <li>
                        <x-global.icon_link icon_path="assets/svg/agenda.svg" :route="route('agenda')"
                                            title="Vers votre agenda" :isActive="request()->routeIs('agenda')">
                            Agenda
                        </x-global.icon_link>
                    </li>
                    <li>
                        <x-global.icon_link icon_path="assets/svg/clients.svg" :route="route('clients.index')"
                                            title="Vers le tableau des clients"
                                            :isActive="request()->routeIs('clients.index')">
                            Clients
                        </x-global.icon_link>
                    </li>
                    <li>
                        <x-global.icon_link icon_path="assets/svg/stats.svg" :route="route('statistics')"
                                            title="Vers la page de vos statitstiques"
                                            :isActive="request()->routeIs('statistics')">
                            Statistiques
                        </x-global.icon_link>
                    </li>
                    <li>
                        <x-global.icon_link icon_path="assets/svg/database.svg" :route="route('database.index')"
                                            title="Vers le dashboard" :isActive="request()->routeIs('database.index')">
                            Données
                        </x-global.icon_link>
                    </li>
                </ul>
            </nav>
        </div>
        <div>
            <x-global.logout/>
            <x-global.icon_link icon_path="assets/svg/profile.svg" :route="route('dashboard')" title="Vers le dashboard">
                {{ $this->authUser->name }}
            </x-global.icon_link>
        </div>
    </aside>
</div>
