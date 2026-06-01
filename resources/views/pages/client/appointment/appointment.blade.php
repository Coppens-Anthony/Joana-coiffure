<x-client.layout title="Prenez rendez-vous" :isContactOrAppointment="true">
    <form action="{{ route('appointment.store') }}" method="post">
        @csrf
        <x-client.section.prices :isAppointment="true" :services="$services" :selectedServices="$selectedServices"/>
        <x-global.link-button.button title="Continuer" class="ml-auto block js:hidden">Confirmer</x-global.link-button.button>

        <x-client.single_element.appointment_bottom_bar/>
    </form>
</x-client.layout>
