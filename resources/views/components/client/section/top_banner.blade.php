<section
    class="bg-[url(../../public/assets/img/originals/hero.jpg)] bg-no-repeat bg-cover max-w-screen h-[calc(100vh-120px)] mb-32 flex items-center justify-center">
    <div class="text-center flex flex-col gap-16 items-center" itemtype="https://schema.org/Organization" itemscope>
        <h2 class="text-[4rem] font-bold text-white" itemprop="legalName">Joana Coiffure</h2>
        <p class="text-[2rem] text-white" itemprop="keywords">Coiffeuse & visagiste indépendante à Orp-Jauche</p>
        <div class="flex gap-4 mx-auto w-fit">
            <x-global.linkbutton.link_button :route="route('contact')" title="Vers la page de contact">Contact
            </x-global.linkbutton.link_button>
            <x-global.linkbutton.link_button :route="route('appointment')" title="Prendre rendez-vous"
                                             :isSecondary="true">
                Rendez-vous
            </x-global.linkbutton.link_button>
        </div>
    </div>
</section>
