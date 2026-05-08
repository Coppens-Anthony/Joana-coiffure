@props(['services', 'totalDuration', 'start_at' => ''])

<section class="w-full md:w-1/2">
    <h2 class="text-[2rem] mb-8">Votre rendez-vous</h2>
    <ul class="flex flex-col gap-4">
        @foreach($services as $service)
            <li>
                <x-client.single_element.appointment :title="$service['name']" :duration="$service->durationFormat($service['duration'])" :price="$service['price']"
                                                     :desc="$service['desc']"/>
            </li>
        @endforeach
        <li>
            <div
                class="bg-tertiary p-8 flex gap-8 justify-between items-center rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.1)]">
                @if($start_at)
                    <p>Durée approximative du rendez-vous : {{ $services->first()->durationFormat($totalDuration) }},
                        le {{ $startAt->isoFormat('D MMMM YYYY') }} à {{ $startAt->format('G\hi') }}</p>
                @else
                    <p>Durée approximative du rendez-vous : {{ $services->first()->durationFormat($totalDuration) }}</p>
                @endif
                    <p>{{ $services->sum('price') }}€</p>
            </div>
        </li>
    </ul>
</section>
