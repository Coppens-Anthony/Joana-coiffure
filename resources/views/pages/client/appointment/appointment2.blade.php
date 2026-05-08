<x-client.layout title="Choix de la date et de l'heure" :isContactOrAppointment="true">
    <div class="flex flex-col gap-8">
        <x-global.linkButton.link class="w-fit" title="Vers l'étape précédente" :route="route('appointment')">
            &laquo; Précédent
        </x-global.linkButton.link>
        <div class="flex flex-col md:flex-row gap-16">
            <section class="w-full md:w-1/2">
                <h3 class="text-[2rem] mb-8">Sélectionnez une date et une heure</h3>
                <form method="get" class="flex flex-col gap-4 mb-8">
                    <x-global.form.input type="date" name="date" value="{{ $dateValue ?? '' }}">
                        Date du rendez-vous
                    </x-global.form.input>
                    @if(isset($error))
                        <small class="text-error">{{ $error }}</small>
                    @endif
                    <x-global.linkButton.button title="Voir les disponibilités">Voir les disponibilités
                    </x-global.linkButton.button>
                </form>
                @if(count($slots))
                    <form method="POST" action="{{ route('appointment2.store') }}">
                        @csrf
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            <input type="hidden" name="date" value="{{ $dateValue }}">
                            @foreach($slots as $slot)
                                <label>
                                    <input
                                        type="radio"
                                        name="slot"
                                        value="{{ $slot['start'] }}"
                                        class="w-4 h-4 border-2 cursor-pointer border-primary accent-primary appearance-none rounded-full checked:bg-primary transition"
                                        {{ $selectedSlot === $slot['start'] ? 'checked' : '' }}
                                    >
                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                </label>
                            @endforeach
                        </div>
                        <x-global.linkButton.button title="Passer à la confirmation">Continuer
                        </x-global.linkButton.button>
                @endif
            </section>
            <x-client.section.appointment_recap :services="$services" :totalDuration="$totalDuration"/>
        </div>
    </div>
</x-client.layout>
