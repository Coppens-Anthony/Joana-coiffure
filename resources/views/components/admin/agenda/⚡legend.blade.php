<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public User $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    #[Computed]
    public function users()
    {
        return User::where('isAdmin', false)->get();
    }
};
?>

<div class="w-fit mx-auto mb-8">
    <ul class="grid grid-cols-2  xl:grid-cols-3 gap-4">
        @if($this->user->isAdmin)
            @foreach($this->users as $user)
                <li class="flex gap-2 items-center">
                    <span class="w-4 h-4 rounded-full block" style="background-color: {{ $user->color }}"></span>Rendez-vous
                    de {{ $user->name }}
                </li>
            @endforeach
        @else
            <li class="flex gap-2 items-center">
                <span class="w-4 h-4 rounded-full block" style="background-color: {{ $this->user->color }}"></span>Rendez-vous
            </li>
        @endif
        <li class="flex gap-2 items-center">
            <span class="w-4 h-4 rounded-full block bg-error"></span>Créneau indisponible
        </li>
        <li class="flex gap-2 items-center">
            <span class="w-4 h-4 rounded-full block bg-dayOff"></span>Journée indisponible
        </li>
    </ul>
</div>
