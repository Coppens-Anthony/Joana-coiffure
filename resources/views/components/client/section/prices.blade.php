@props(['services', 'isAppointment' => false, 'selectedServices' => []])

<section>
    <h2 class="text-[2rem] mb-16">{{ $isAppointment ? 'Sélectionnez une/des prestation/s' : 'Mes tarifs'}}</h2>
    <form action="{{ route('appointment.store') }}" method="post">
        @csrf
        <ul class="flex flex-col gap-8">
            @foreach($services as $service)
                <li class="border-b border-black pb-4 flex flex-col gap-4 md:flex-row md:gap-8">
                    <x-client.single_element.price_line :service="$service" :selectedServices="$selectedServices" :isAppointment="$isAppointment"/>
                </li>
            @endforeach
        </ul>
        <small class="text-[0.75rem] italic mt-4 block mb-4">* Tout soin est compris dans le tarif de la prestation.</small>
        <x-global.linkButton.button title="Continuer" class="ml-auto block">Confirmer</x-global.linkButton.button>
    </form>
</section>
