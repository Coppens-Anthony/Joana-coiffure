<x-client.layout title="Prenez rendez-vous" :isContactOrAppointment="true">
    <x-client.section.prices :isAppointment="true" :services="$services"/>
    <x-client.single_element.appointment_bottom_bar/>
</x-client.layout>
