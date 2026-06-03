<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public bool $isMenuOpen = false;

    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }

    public function toggleMenu()
    {
        $this->isMenuOpen = !$this->isMenuOpen;
    }
};
?>

<div>
    @if($isMenuOpen)
        <button
            wire:click="toggleMenu"
            class="fixed inset-0 bg-black/50 z-40 md:hidden cursor-pointer"
            aria-label="Fermer le menu"></button>
    @endif

    <button
        wire:click="toggleMenu"
        class="fixed top-8.5 right-8 z-50 flex md:hidden flex-col justify-center gap-1.5 bg-white p-3 rounded-xl shadow-[0_0_10px_rgba(0,0,0,0.25)]"
        aria-label="Ouvrir le menu">
        <span class="w-6 h-0.5 rounded-2xl bg-black"></span>
        <span class="w-6 h-0.5 rounded-2xl bg-black"></span>
        <span class="w-6 h-0.5 rounded-2xl bg-black"></span>
    </button>

    <aside
        class="fixed md:sticky top-0 right-0 md:left-0 z-50
        p-4 flex flex-col justify-between
        h-screen w-fit
        bg-white
        md:shadow-[0_0_10px_rgba(0,0,0,0.25)]
        rounded-bl-2xl md:rounded-bl-none
        rounded-tl-2xl md:rounded-tl-none
        rounded-br-none md:rounded-br-2xl
        rounded-tr-none md:rounded-tr-2xl
        transition-transform duration-300
        {{ $isMenuOpen ? 'translate-x-0' : 'translate-x-full' }}
        md:translate-x-0">
        <h2 class="sr-only">Barre latérale</h2>
        <div>
            <div class="flex justify-between md:block h-fit">
                <div class="relative w-fit mx-auto h-fit mb-4">
                    <a tabindex="1"
                        href="{{ route('dashboard') }}"
                        title="Revenir au dashboard"
                        class="absolute inset-0"></a>

                    <x-global.logo/>

                </div>
                <img src="{{ asset('assets/svg/close.svg') }}" alt="Fermer le menu" wire:click="toggleMenu" class="cursor-pointer md:hidden h-fit">
            </div>

            <nav>
                <h3 class="sr-only">Navigation</h3>

                <ul class="flex flex-col gap-4 pt-4 border-t border-black">
                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/dashboard.svg"
                            :route="route('dashboard')"
                            title="Vers le dashboard"
                            :isActive="request()->routeIs('dashboard')"
                        >
                            Dashboard
                        </x-global.link-button.icon-link>
                    </li>

                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/agenda.svg"
                            :route="route('agenda')"
                            title="Vers votre agenda"
                            :isActive="request()->routeIs('agenda')"
                        >
                            Agenda
                        </x-global.link-button.icon-link>
                    </li>

                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/clients.svg"
                            :route="route('clients.index')"
                            title="Vers le tableau des clients"
                            :isActive="request()->routeIs('clients.*')"
                        >
                            Clients
                        </x-global.link-button.icon-link>
                    </li>

                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/stats.svg"
                            :route="route('statistics')"
                            title="Vers la page de vos statistiques"
                            :isActive="request()->routeIs('statistics')"
                        >
                            Statistiques
                        </x-global.link-button.icon-link>
                    </li>

                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/database.svg"
                            :route="route('database.services')"
                            title="Vers les données"
                            :isActive="request()->routeIs('database.services')"
                        >
                            Prestations
                        </x-global.link-button.icon-link>
                    </li>
                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/gallery.svg"
                            :route="route('database.gallery')"
                            title="Vers les données"
                            :isActive="request()->routeIs('database.gallery')"
                        >
                            Galerie
                        </x-global.link-button.icon-link>
                    </li>
                    <li>
                        <x-global.link-button.icon-link
                            tabindex="1"
                            icon_path="assets/svg/off.svg"
                            :route="route('recurring_unavailabilities')"
                            title="Vers les congés récurents"
                            :isActive="request()->routeIs('recurring_unavailabilities')"
                        >
                            Congés récurents
                        </x-global.link-button.icon-link>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="flex flex-col gap-4">
            <x-global.logout/>

            <x-global.link-button.icon-link
                tabindex="1"
                icon_path="assets/svg/profile.svg"
                :route="route('profile')"
                :isActive="request()->routeIs('profile')"
                title="Vers le profil">
                {{ $this->authUser->name }}
            </x-global.link-button.icon-link>
        </div>
    </aside>
</div>
