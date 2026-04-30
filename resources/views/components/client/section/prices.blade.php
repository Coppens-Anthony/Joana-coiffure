@props(['services'])

<section>
    <h2 class="text-[2rem] mb-16">Mes tarifs</h2>
    <ul class="flex flex-col gap-8">
        @foreach($services as $service)
            <li class="border-b border-black pb-4 flex flex-col gap-4">
                <x-client.single_element.price_line :name="$service->name" :duration="$service->duration" :price="$service->price" :desc="$service->desc"/>
            </li>
        @endforeach
    </ul>
    <small class="text-[0.75rem] italic mt-4 block">* Tout soin est compris dans le tarif de la prestation.</small>
</section>
