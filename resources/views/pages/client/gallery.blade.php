<x-client.layout title="Mon travail en images" :isContactOrAppointment="false">
    <section>
        <h2 class="sr-only">Galerie</h2>
        <ul class="flex flex-col gap-8">
            @foreach($photos->chunk(7) as $chunk)
                <li>
                    <ul class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-8 auto-rows-[240px]">
                        @foreach($chunk as $index => $photo)
                            <li @class([
                        'col-start-1 row-start-1 row-span-2 md:col-start-1 md:row-start-1 md:row-span-2' => $index % 7 === 0,
                        'col-start-2 row-start-1 md:col-start-2 md:row-start-1'                          => $index % 7 === 1,
                        'col-start-2 row-start-2 md:col-start-2 md:row-start-2'                          => $index % 7 === 2,
                        'col-start-1 row-start-3 md:col-start-3 md:row-start-1 md:row-span-2'            => $index % 7 === 3,
                        'col-start-2 row-start-3 md:col-start-4 md:row-start-1'                          => $index % 7 === 4,
                        'col-start-1 row-start-4 md:col-start-4 md:row-start-2'                          => $index % 7 === 5,
                        'col-start-2 row-start-4 md:col-start-5 md:row-start-1 md:row-span-2'            => $index % 7 === 6,
                    ])>
                                <img src="{{ Storage::url('pictures/originals/' . $photo->picture) }}"
                                     srcset="
                                {{ Storage::url('pictures/variants/300x300/' . $photo->picture) }} 300w,
                                {{ Storage::url('pictures/variants/600x600/' . $photo->picture) }} 600w,
                                {{ Storage::url('pictures/variants/900x900/' . $photo->picture) }} 900w"
                                     sizes="(max-width: 768px) 50vw, 20vw"
                                     alt=""
                                     class="h-full w-full object-cover rounded-2xl"
                                    {{ $loop->index >= 7 ? 'loading=lazy' : '' }}>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </section>
</x-client.layout>


{{--
POUR LA PROD :
<img src="{{Storage::url('pictures/originals/'.$photo->picture)}}"
     srcset="
                {{Storage::url('pictures/variants/300x300/'.$photo->picture)}} 300w,
                {{Storage::url('pictures/variants/600x600/'.$photo->picture)}} 600w,
                {{Storage::url('pictures/variants/900x900/'.$photo->picture)}} 900w"
     sizes="(max-width: 768px) 100vw, 50vw"
     alt=""
     class="w-full h-full rounded-4xl object-cover">
--}}
