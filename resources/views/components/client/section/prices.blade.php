@props(['services', 'isAppointment' => false])

<section>
    <h2 class="text-[2rem] mb-16">{{ $isAppointment ? 'Sélectionnez une/des prestation/s' : 'Mes tarifs'}}</h2>
    <ul class="flex flex-col gap-8">
        @foreach($services as $service)
            <li class="border-b border-black pb-4 flex flex-col gap-4 md:flex-row md:gap-8">
                <x-client.single_element.price_line :isAppointment="$isAppointment" :name="$service->name" :duration="$service->durationFormat($service->duration)" :price="$service->price" :desc="$service->desc"/>
            </li>
        @endforeach
    </ul>
    <small class="text-[0.75rem] italic mt-4 block">* Tout soin est compris dans le tarif de la prestation.</small>
</section>
