<?php

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Fiche du membre')]
class extends Component {
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }
};
?>

<div>
    <section class="flex flex-col sm:flex-row gap-8 items-center sm:text-start">
        <div class="h-32 w-32 sm:h-48 sm:w-48 rounded-full overflow-hidden bg-tertiary border border-black">
            @if($this->user->avatar)
                <img src="{{ Storage::url('pictures/originals/' . $this->user->avatar) }}"
                     srcset="{{ Storage::url('pictures/variants/300x300/' . $this->user->avatar) }} 300w,
                            {{ Storage::url('pictures/variants/600x600/' . $this->user->avatar) }} 600w"
                     sizes="176px"
                     alt="Photo de {{ $this->user->name }}"
                     class="h-full w-full object-cover">
            @else
                <div class="h-full w-full flex items-center justify-center text-4xl">
                    {{ strtoupper(substr($this->user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-4">
            <h2 class="text-2xl">{{ $this->user->name }}</h2>
            <p>{{ $this->user->email }}</p>
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-full border-4 border-white shadow-lg ring-1 ring-black/10"
                    style="background-color: {{ $this->user->color }}"></div>
                <div>
                    <p>Couleur personnelle</p>
                    <span class="text-[.85rem] w-fit block mr-auto">{{ $this->user->color }}</span>
                </div>
            </div>
        </div>
    </section>
</div>
