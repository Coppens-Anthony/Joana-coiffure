<x-client.layout title="Prenez rendez-vous" :isContactOrAppointment="true">
    <div class="flex flex-col gap-8">
        <div class="flex justify-between w-full">
            @if(session('appointment.services'))
                <x-global.link-button.link class="w-fit ml-auto" title="Vers l'étape suivante" :route="route('appointment2')">
                    Suivant &raquo;
                </x-global.link-button.link>
            @endif
        </div>
        <form action="{{ route('appointment.store') }}" method="post">
            @csrf
            <x-client.section.prices :isAppointment="true" :services="$services" :selectedServices="$selectedServices"/>
            <x-global.link-button.button title="Continuer" class="ml-auto block js:hidden">Confirmer
            </x-global.link-button.button>

            <x-client.single_element.appointment_bottom_bar/>
        </form>
    </div>
</x-client.layout>
