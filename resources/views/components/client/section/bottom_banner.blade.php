<section
    class="bg-[url(../../public/assets/img/hero.png)] bg-no-repeat bg-cover p-16 mt-32 max-w-screen mb-16 flex items-center justify-center">
    <div class="text-center flex flex-col gap-16 items-center">
        <h2 class="text-[4rem] font-bold text-white">Prêt(e) pour votre prochaine coupe ?</h2>
        <p class="text-[2rem] text-white">Prenez rendez-vous en quelques clics ou contactez-moi pour toute
            question.</p>
        <div class="flex gap-4 mx-auto w-fit">
            <x-global.link_button :route="route('appointment')" title="Prendre rendez-vous">
                Rendez-vous
            </x-global.link_button>
            <x-global.link_button :route="route('contact')" title="Vers la page de contact" :isSecondary="true">
                Contact
            </x-global.link_button>
        </div>
    </div>
</section>
