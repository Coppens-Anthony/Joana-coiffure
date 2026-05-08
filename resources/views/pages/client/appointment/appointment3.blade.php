<x-client.layout title="Confirmation du rendez-vous" :isContactOrAppointment="true">
    <div class="flex flex-col gap-8">
        <x-global.linkButton.link class="w-fit" title="Vers l'étape précédente" :route="route('appointment2')">&laquo; Précédent
        </x-global.linkButton.link>
        <div class="flex flex-col md:flex-row gap-16">
            <x-client.section.appointment_form/>
            <x-client.section.appointment_recap :services="$services" :totalDuration="$totalDuration"
                                                :start_at="$start_at"/>
        </div>
    </div>
</x-client.layout>
