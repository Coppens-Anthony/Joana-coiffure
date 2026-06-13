<section
    class="bg-[url(../../public/assets/img/originals/hero_mobile.jpg)] md:bg-[url(../../public/assets/img/originals/hero.jpg)] bg-no-repeat bg-cover p-16 mt-32 max-w-screen mb-16 flex items-center justify-center">
    <div class="text-center flex flex-col gap-16 items-center">
        <h2 class="text-[2rem] md:text-[4rem] font-bold text-white">Prêt(e) pour votre prochaine coupe ?</h2>
        <p class="text-xl md:text-[2rem] text-white">Prenez rendez-vous en quelques clics ou contactez-moi pour toute
            question.</p>
        <div class="flex flex-col md:flex-row items-center gap-4 mx-auto w-fit">
            <x-global.link-button.link-button :route="route('appointment')" title="Prendre rendez-vous">
                Rendez-vous
            </x-global.link-button.link-button>
            <x-global.link-button.link-button :route="route('contact')" title="Vers la page de contact" :isSecondary="true">
                Contact
            </x-global.link-button.link-button>
        </div>
    </div>
</section>
