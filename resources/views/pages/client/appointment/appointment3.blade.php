<x-client.layout title="Choix de la date et de l'heure" :isContactOrAppointment="true">
    @if(session('error'))
        <div class="alert-delete">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex flex-col gap-8">
        <div class="flex justify-between w-full">
            <x-global.link-button.link class="w-fit" title="Vers l'étape précédente" :route="route('appointment2')">
                &laquo; Précédent
            </x-global.link-button.link>
            @if(session('appointment.date') && session('appointment.slot'))
                <x-global.link-button.link class="w-fit" title="Vers l'étape suivante" :route="route('appointment4')">
                    Suivant &raquo;
                </x-global.link-button.link>
            @endif
        </div>
        <div class="flex flex-col-reverse md:flex-row gap-16">
            <section class="w-full md:w-1/2">
                <h3 class="text-[2rem] mb-8">Sélectionnez une date et une heure</h3>
                <div class="p-8 rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.1)]">
                    <div class="flex gap-2 items-center mb-4">
                        <span class="w-2 h-2 bg-error rounded-full"></span>
                        <small class="text-[.85rem] italic">Jours indisponibles</small>
                    </div>
                    <div class="grid grid-cols-3 items-center mb-8">
                        <div class="relative w-fit">
                            @if(!$currentMonth->isSameMonth(today()))
                                @php
                                    $previousMonth = $currentMonth->copy()->subMonth();
                                    $previousDate = $previousMonth->isSameMonth(today())
                                        ? today()
                                        : $previousMonth->startOfMonth();
                                @endphp
                                <a href="?date={{ $previousDate->format('Y-m-d') }}" title="Aller au mois précédent"
                                   class="absolute w-full h-full cursor-pointer z-10"></a>
                                <img src="{{ asset('assets/svg/chevron.svg') }}" alt="Aller au mois précédent"
                                     class="rotate-90">
                            @endif
                        </div>

                        <p class="text-center text-xl">
                            {{ ucfirst($currentMonth->translatedFormat('F Y')) }}
                        </p>

                        <div class="flex justify-end ml-auto relative w-fit">
                            @php
                                $nextMonth = $currentMonth->copy()->addMonth();

                                $nextDate = $nextMonth->startOfMonth();
                            @endphp

                            <a href="?date={{ $nextDate->format('Y-m-d') }}" title="Aller au mois suivant"
                               class="absolute w-full h-full cursor-pointer z-10"></a>
                            <img src="{{ asset('assets/svg/chevron.svg') }}" alt="Aller au mois suivant"
                                 class="-rotate-90">
                        </div>

                    </div>
                    <div class="grid grid-cols-7 text-center text-sm mb-2 opacity-60">
                        <p>Lun</p>
                        <p>Mar</p>
                        <p>Mer</p>
                        <p>Jeu</p>
                        <p>Ven</p>
                        <p>Sam</p>
                        <p>Dim</p>
                    </div>
                    <div class="grid grid-cols-7 gap-2 w-full">

                        @foreach($days as $day)

                            @php
                                $isCurrentMonth = $day->month === $currentMonth->month;
                                $isSelected = $day->isSameDay($currentDate);
                                $isPast = $day->isBefore(today());
                                $hasSlots = $availableDays[$day->format('Y-m-d')] ?? true;
                            @endphp

                            <a href="?date={{ $day->format('Y-m-d') }}#slots"
                               tabindex="{{ $isPast || !$hasSlots || !$isCurrentMonth ? '-1' : '0' }}"
                               class="w-full py-2 flex items-center justify-center rounded-full font-bold transition hover:bg-primary
                                {{ !$isCurrentMonth ? 'opacity-20 pointer-events-none' : '' }}
                                {{ $isPast ? 'opacity-30 pointer-events-none' : '' }}
                                {{ $isSelected ? 'bg-primary hover:bg-primary text-black' : '' }}
                                {{ !$isPast && $isCurrentMonth && !$hasSlots ? 'text-error pointer-events-none' : '' }}">
                                {{ $day->day }}
                            </a>
                        @endforeach
                    </div>
                    @if(count($slots))
                        <div class="grid grid-cols-4 md:grid-cols-6 gap-4 mt-4" id="slots">
                            @foreach($slots as $slot)
                                <form method="POST" action="{{ route('appointment3.store') }}">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $dateValue }}">
                                    <input type="hidden" name="slot" value="{{ $slot['start'] }}">
                                    <button type="submit"
                                            class="text-center w-full px-2 md:px-0 py-2 hover:bg-white border-2 border-primary duration-200 cursor-pointer bg-primary rounded-xl">{{ $slot['start'] }}</button>
                                </form>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm opacity-60">
                            Aucun créneau disponible pour cette journée.
                        </p>
                    @endif
                </div>
            </section>
            <x-client.section.appointment_recap :services="$services" :totalDuration="$totalDuration"/>
        </div>
    </div>
</x-client.layout>
