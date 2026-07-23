@php use Carbon\Carbon; @endphp
<x-client.layout title="Annulation du rendez-vous" :isContactOrAppointment="true">
    <section class="flex flex-col md:flex-row items-center gap-8 md:gap-16">
        <div class="flex flex-col gap-4 md:gap-8 md:w-1/2">
            <h2 class="text-[2rem]">Annuler votre rendez-vous</h2>
            <p>
                Vous êtes sur le point d'annuler votre rendez-vous. Cette action est définitive.
            </p>
            <ul class="flex flex-col gap-4 list-disc ml-4">
                <li>
                    {!! $appointment->services->map(fn($service) => $service->name)->implode(', ') !!} ;
                </li>
                <li>
                    {{ $appointment->formatDate('start_at') . ' de ' . Carbon::parse($appointment->start_at)->format('H\hi') . ' à ' . Carbon::parse($appointment->end_at)->format('H\hi') }};
                </li>
                <li>
                    {{ $appointment->services->sum('price') }}€ ;
                </li>
            </ul>
            <form action="{{ route('appointment_cancel', $appointment) }}"
                  method="POST"
                  class="flex flex-col sm:flex-row gap-4">
                @csrf
                <button class="px-8 py-4 duration-200 w-fit text-white rounded-full cursor-pointer bg-error border-2 border-error hover:bg-white hover:text-error">
                    Annuler le rendez-vous
                </button>
            </form>
        </div>
        <div class="w-full md:w-1/2">
            <img src="{{ asset('assets/img/originals/cancel.jpg') }}" alt="" class="rounded-[3rem]">
        </div>
    </section>
</x-client.layout>
