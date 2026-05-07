<footer class="px-8 lg:px-16 mt-16 pb-8">
    <section class="flex gap-6 flex-col md:grid md:grid-cols-2 lg:flex lg:flex-row lg:justify-between mb-16">
        <h2 class="sr-only">Pied de page</h2>
        <div class="w-fit">
            <div class="relative" itemtype="https://schema.org/Organization" itemscope>
                <a href="/" class="absolute h-full w-full"></a>
                <img src="" alt="LOGO" itemprop="logo">
            </div>
            <div class="relative w-fit">
                <a href="https://www.facebook.com/profile.php?id=100040838886459&locale=fr_FR" target="_blank"
                   class="absolute top-0 left-0 h-full w-full" title="Vers ma page Facebook"></a>
                <img src="{{asset('assets/svg/facebook.svg')}}" alt="Vers ma page Facebook">
            </div>
        </div>
        <section>
            <h3 class="text-[1.5rem] mb-4">Mes horaires</h3>
            <ul class="flex flex-col gap-4">
                <li>
                    Du lundi au vendredi de 9h à 19h
                </li>
                <li>
                    Le samedi de 9h à 18h
                </li>
                <li>
                    Fermé le dimanche
                </li>
            </ul>
        </section>
        <nav aria-labelledby="navigation-bas-de-page">
            <h3 id="navigation-bas-de-page" class="text-[1.5rem] mb-4">
                Navigation <span class="sr-only">secondaire</span>
            </h3>
            <ul class="flex flex-col gap-4">
                <li>
                    <x-global.linkbutton.link :route="route('home')" title="Vers la page d'accueil">Accueil</x-global.linkbutton.link>
                </li>
                <li>
                    <x-global.linkbutton.link :route="route('about')" title="Vers la page à propos">À propos</x-global.linkbutton.link>
                </li>
                <li>
                    <x-global.linkbutton.link :route="route('prices')" title="Vers la page des tarifs">Tarifs</x-global.linkbutton.link>
                </li>
                <li>
                    <x-global.linkbutton.link :route="route('gallery')" title="Vers la galerie">Galerie</x-global.linkbutton.link>
                </li>
                <li>
                    <x-global.linkbutton.link :route="route('contact')" title="Vers la page de contact">Contact</x-global.linkbutton.link>
                </li>
                <li>
                    <x-global.linkbutton.link :route="route('appointment')" title="Prendre un rendez-vous">Rendez-vous</x-global.linkbutton.link>
                </li>
            </ul>
        </nav>
        <section>
            <h3 class="text-[1.5rem] mb-4">Mes coordonnées</h3>
            <ul class="flex flex-col gap-4" itemtype="https://schema.org/Organization" itemscope>
                <li itemprop="email">
                    <x-global.linkbutton.link route="mailto:joanastofs@gmail.com" title="Envoyez-moi un mail">joanastofs@gmail.com</x-global.linkbutton.link>
                </li>
                <li itemprop="telephone">
                    <x-global.linkbutton.link route="tel:0466486777" title="Téléphonez-moi">0466 48 67 77</x-global.linkbutton.link>
                </li>
                <li itemprop="address">
                    Rue de la Station 57,
                </li>
                <li itemprop="location">
                    1350 Orp-Jauche
                </li>
            </ul>
        </section>
    </section>
    <section class="flex flex-col gap-2 lg:flex-row justify-between">
        <h2 class="sr-only">Mentions légales</h2>
        <p>© 2026 Joana Coiffure - Tous droits réservés.</p>
        <p>
            Réalisé par
            <x-global.linkbutton.link route="" title="Vers le portfolio d'Anthony Coppens">Anthony Coppens</x-global.linkbutton.link>
            -
            <x-global.linkbutton.link route="" title="Vers les mentions légales">Mentions légales</x-global.linkbutton.link>
        </p>
    </section>
</footer>
