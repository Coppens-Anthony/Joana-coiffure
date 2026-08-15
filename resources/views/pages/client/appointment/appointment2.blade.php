<x-client.layout title="Choix du coiffeur" :isContactOrAppointment="true">
    @if(session('error'))
        <div class="alert-delete">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex flex-col gap-8">
        <div class="flex justify-between w-full">
            <x-global.link-button.link class="w-fit" title="Vers l'étape précédente" :route="route('appointment')">
                &laquo; Précédent
            </x-global.link-button.link>
            @if(session('appointment.user_id'))
                <x-global.link-button.link class="w-fit" title="Vers l'étape suivante" :route="route('appointment3')">
                    Suivant &raquo;
                </x-global.link-button.link>
            @endif
        </div>
        <section>
            <h2 class="text-[2rem] mb-8">
                Choisissez votre coiffeur.se
            </h2>

            <form method="POST" action="{{ route('appointment2.store') }}" class="relative">
                @csrf

                @if($users)
                    <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($users as $user)
                            <li>
                                <label class="block cursor-pointer">
                                    <input
                                        type="radio"
                                        name="user_id"
                                        value="{{ $user->id }}"
                                        class="peer sr-only"
                                        @checked(session('appointment.user_id') == $user->id)
                                    >

                                    <div class="relative overflow-hidden rounded-2xl transition-all duration-200 aspect-square
                               border-2 border-transparent hover:-translate-y-1 peer-checked:border-primary peer-checked:border-4">
                                        @if($user->avatar)
                                            <img
                                                src="{{ Storage::url('pictures/originals/' . $user->avatar) }}"
                                                srcset="{{ Storage::url('pictures/variants/300x300/' . $user->avatar) }} 300w,
                                        {{ Storage::url('pictures/variants/600x600/' . $user->avatar) }} 600w,
                                        {{ Storage::url('pictures/variants/900x900/' . $user->avatar) }} 900w"
                                                sizes="(max-width:768px) 45vw, 25vw"
                                                alt="{{ $user->name }}"
                                                class="h-full w-full object-cover transition-transform duration-200 hover:scale-110">
                                        @else
                                            <div
                                                class="h-full w-full flex items-center justify-center bg-tertiary text-4xl font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div
                                            class="absolute inset-0 bg-linear-to-t from-black/75 via-black/10 to-transparent"></div>

                                        <div class="absolute bottom-0 left-0 right-0 p-4">
                                            <p class="text-white font-semibold text-lg">
                                                {{ $user->name }}
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </li>
                        @endforeach
                    </ul>

                    <x-global.link-button.button title="Vers la prochaine étape"
                                                 class="ml-auto block w-fit mt-8 md:sticky md:bottom-8 md:right-16 z-10">
                        Continuer
                    </x-global.link-button.button>
                @else
                    <p>Désolé, aucun coiffeur n'est disponible.</p>
                @endif

            </form>
        </section>
    </div>
</x-client.layout>
