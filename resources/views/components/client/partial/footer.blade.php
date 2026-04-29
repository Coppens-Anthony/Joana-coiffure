<footer class="px-8 lg:px-16 mt-16 pb-8">
    <section class="flex gap-6 flex-col md:grid md:grid-cols-2 lg:flex lg:flex-row lg:justify-between mb-16">
        <h2 class="sr-only">Pied de page</h2>
        <div class="w-fit">
            <div class="relative">
                <a href="/" class="absolute h-full w-full"></a>
                <img src="" alt="LOGO">
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
                    <x-global.link route="" title="Vers la page d'accueil">Accueil</x-global.link>
                </li>
                <li>
                    <x-global.link route="" title="Vers la page à propos">À propos</x-global.link>
                </li>
                <li>
                    <x-global.link route="" title="Vers la page des tarifs">Tarifs</x-global.link>
                </li>
                <li>
                    <x-global.link route="" title="Vers la galerie">Galerie</x-global.link>
                </li>
                <li>
                    <x-global.link route="" title="Vers la page de contact">Contact</x-global.link>
                </li>
                <li>
                    <x-global.link route="" title="Prendre un rendez-vous">Rendez-vous</x-global.link>
                </li>
            </ul>
        </nav>
        <section>
            <h3 class="text-[1.5rem] mb-4">Mes coordonnées</h3>
            <ul class="flex flex-col gap-4">
                <li>
                    <x-global.link route="mailto:joanastofs@gmail.com" title="Envoyez-moi un mail">joanastofs@gmail.com</x-global.link>
                </li>
                <li>
                    <x-global.link route="tel:0466486777" title="Téléphonez-moi">0466 48 67 77</x-global.link>
                </li>
                <li>
                    Rue et numéro
                </li>
                <li>
                    Code postal et ville
                </li>
            </ul>
        </section>
    </section>
    <section class="flex flex-col gap-2 lg:flex-row justify-between">
        <h2 class="sr-only">Mentions légales</h2>
        <p>© 2026 Joana Coiffure - Tous droits réservés.</p>
        <p>
            Réalisé par
            <x-global.link route="" title="Vers le portfolio d'Anthony Coppens">Anthony Coppens</x-global.link>
            -
            <x-global.link route="" title="Vers les mentions légales">Mentions légales</x-global.link>
        </p>
    </section>
</footer>
