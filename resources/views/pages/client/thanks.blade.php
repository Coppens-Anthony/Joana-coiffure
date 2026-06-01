@php use Carbon\Carbon; @endphp
<x-client.layout :isContactOrAppointment="true" title="Confirmation du rendez-vous">
    <section class="flex flex-col md:flex-row items-center gap-8 md:gap-16">
        <div class="flex flex-col gap-4 md:gap-8 md:w-1/2">
            <h2 class="text-[2rem]">Merci {{ $appointment->client->name }} d'avoir pris rendez-vous chez Joana-Coiffure&nbsp;!</h2>
            <ul class="flex flex-col gap-4 list-disc ml-4">
                <li>
                    {!! $appointment->services->map(fn($service) => $service->name)->implode(', ') !!} ;
                </li>
                <li>
                    {{ $appointment->formatDate('start_at') . ' de ' . Carbon::parse($appointment->start_at)->format('H\hi') . ' à ' . Carbon::parse($appointment->end_at)->format('H\hi') }}
                    ;
                </li>
                <li>
                    {{ $appointment->services->sum('price') }}€ ;
                </li>
                <li>
                    Paiement sur place en liquide.
                </li>
            </ul>
            <x-global.link-button.link-button :route="route('home')" title="Revenir à l'accueil">Revenir à l'accueil</x-global.link-button.link-button>
        </div>
        <div class="w-full md:w-1/2">
            <iframe class="rounded-[3rem]"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2527.210133681018!2d4.984718775267964!3d50.697484969547745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c10cd5588e262d%3A0x844aabd07ff11a72!2sRue%20de%20la%20Station%2057%2C%201350%20Orp-Jauche!5e0!3m2!1sfr!2sbe!4v1778491318035!5m2!1sfr!2sbe"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

</x-client.layout>
