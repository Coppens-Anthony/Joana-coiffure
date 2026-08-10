<x-client.layout title="Contactez-moi" :isContactOrAppointment="true">
    <section class="flex flex-col md:flex-row gap-16">
        @if(session('success'))
            <div class="bg-secondary m-auto w-full md:w-1/2 rounded-3xl p-8 h-fit text-center">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h2 class="text-2xl font-bold  mb-2">
                    Votre message a bien été envoyé&nbsp;!
                </h2>
                <p>
                    Nous vous recontacterons le plus vite possible.
                </p>
            </div>
        @else
            <x-client.section.contact_form/>
        @endif
        <x-client.section.contact_information/>
    </section>
</x-client.layout>
