<x-client.layout title="Prenez rendez-vous" :isContactOrAppointment="true">
    <x-client.section.prices :isAppointment="true" :services="$services" :selectedServices="$selectedServices"/>
    {{--<x-client.single_element.appointment_bottom_bar/>--}}
</x-client.layout>
