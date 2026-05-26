<header class="flex items-center justify-between h-30 px-8 py-8 md:px-12 lg:px-16 {{ request()->routeIs('home') ? '' : 'mb-16' }} z-50 bg-white sticky top-0 shadow-md">
    <div class="relative z-50">
        <a href="{{route('home')}}" class="absolute top-0 left-0 w-full h-full"
           title="Vers la page d'accueil"></a>
        <x-global.logo/>
    </div>
    <input type="checkbox" id="menu-toggle" class="peer hidden"/>
    <label for="menu-toggle" class="flex flex-col gap-1 justify-between cursor-pointer z-50 md:invisible">
        <span class="span_burger_menu"></span>
        <span class="span_burger_menu"></span>
        <span class="span_burger_menu"></span>
    </label>
    <nav aria-labelledby="navigation-haut-de-page"
         class="fixed inset-0 bg-white text-center transform translate-x-full
         peer-checked:translate-x-0 transition-transform duration-700 ease-in-out z-40
         md:static md:translate-x-0 md:flex md:justify-between
         ">
        <h2 class="sr-only" id="navigation-haut-de-page">Navigation</h2>
        <ul class="flex flex-col w-full gap-6 origin-center absolute top-[25%] left-1/2 -translate-x-1/2
            md:flex-row md:static md:translate-x-0 md:top-auto md:w-auto md:justify-between md:items-center">
            <li>
                <x-global.linkbutton.link
                    route="{{ route('home') }}"
                    title="Vers la page d'accueil"
                    :isActive="request()->routeIs('home')">
                    Accueil
                </x-global.linkbutton.link>
            </li>
            <li>
                <x-global.linkbutton.link
                    route="{{ route('about') }}"
                    title="Vers la page à propos"
                    :isActive="request()->routeIs('about')">
                    À propos
                </x-global.linkbutton.link>
            </li>
            <li>
                <x-global.linkbutton.link
                    route="{{ route('prices') }}"
                    title="Vers la page des tarifs"
                    :isActive="request()->routeIs('prices')">
                    Tarifs
                </x-global.linkbutton.link>
            </li>
            <li>
                <x-global.linkbutton.link
                    route="{{ route('gallery') }}"
                    title="Vers la galerie"
                    :isActive="request()->routeIs('gallery')">
                    Galerie
                </x-global.linkbutton.link>
            </li>
            <li>
                <x-global.linkbutton.link
                    route="{{ route('contact') }}"
                    title="Vers la page de contact"
                    :isActive="request()->routeIs('contact')">
                    Contact
                </x-global.linkbutton.link>
            </li>
            <x-global.linkbutton.link_button class="mx-auto" route="{{ route('appointment') }}" title="Prendre un rendez-vous">Rendez-vous
            </x-global.linkbutton.link_button>
        </ul>
    </nav>
</header>

