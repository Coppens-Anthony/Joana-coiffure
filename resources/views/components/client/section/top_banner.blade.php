<section
    class="bg-[url(../../public/assets/img/originals/hero_mobile.jpg)] md:bg-[url(../../public/assets/img/originals/hero.jpg)] bg-no-repeat bg-cover max-w-screen h-[calc(100vh-120px)] mb-32 flex items-center justify-center">
    <div class="text-center mx-8 md:mx-16 flex flex-col gap-16 items-center" itemtype="https://schema.org/Organization" itemscope>
        <h2 class="text-[2rem] md:text-[4rem] font-bold text-white" itemprop="legalName">Joana-Coiffure</h2>
        <p class="text-xl md:text-[2rem] text-white" itemprop="keywords">Coiffeuse & visagiste indépendante à Orp-Jauche</p>
        <div class="flex flex-col md:flex-row items-center gap-4 mx-auto w-fit">
            <x-global.link-button.link-button :route="route('appointment')" title="Prendre rendez-vous">
                Rendez-vous
            </x-global.link-button.link-button>
            <x-global.link-button.link-button :isSecondary="true" :route="route('contact')" title="Vers la page de contact">
                Contact
            </x-global.link-button.link-button>
        </div>
    </div>
</section>
