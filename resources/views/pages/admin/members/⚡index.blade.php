<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Les membres')]
class extends Component {
    #[On('action_done')]
    public function refresh(string $message = '')
    {
        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }

    #[Computed]
    public function users()
    {
        return User::all();
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::user.create']);
    }
};
?>

<div>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <section>
        <h2 class="sr-only">Liste des membres du salon</h2>
        <x-global.link-button.button-link title="Ajouter un membre" class="block ml-auto mb-8" wire:click="create">
            Ajouter un membre
        </x-global.link-button.button-link>
        @if($this->users)
            <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-6">
                @foreach($this->users as $user)
                    <li class="relative">
                        <a href="{{ $this->authUser == $user ? route('profile') : route('members.show', $user) }}"
                           wire:navigate
                           title="Vers la fiche de {{ $user->name }}"
                           class="group block relative overflow-hidden rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-200 aspect-square">

                            @if($user->avatar)
                                <img src="{{ Storage::url('pictures/originals/' . $user->avatar) }}"
                                     srcset="{{ Storage::url('pictures/variants/300x300/' . $user->avatar) }} 300w,
                                             {{ Storage::url('pictures/variants/600x600/' . $user->avatar) }} 600w,
                                             {{ Storage::url('pictures/variants/900x900/' . $user->avatar) }} 900w"
                                     sizes="(max-width: 768px) 45vw, 25vw"
                                     alt="Photo de {{ $user->name }}"
                                     loading="lazy"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div
                                    class="h-full w-full flex items-center justify-center bg-tertiary text-4xl font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/0 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <h3 class="text-white font-medium text-sm sm:text-base truncate drop-shadow">
                                    {{ $user->name }}
                                </h3>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Aucun membre renseingé.</p>
        @endif
    </section>
</div>
