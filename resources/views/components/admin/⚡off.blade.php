<?php

use App\Models\Unavailability;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public User $user;
    public array $unavailability;
    public bool $isReadOnly;
    public Unavailability $test;

    public function mount()
    {
        $this->test = Unavailability::findOrFail($this->unavailability['id']);
    }

    #[Computed]
    public function authUser()
    {
      return $this->user = auth()->user();
    }

    public function edit()
    {
        $this->dispatch('open_modal', [
            'modal' => 'modals::unavailabilities.create',
            'params' => [
                'start_date' => Carbon::parse($this->unavailability['start_at'])->toDateString(),
                'end_date' => Carbon::parse($this->unavailability['end_at'])->toDateString(),
                'start_at' => Carbon::parse($this->unavailability['start_at'])->format('H:i'),
                'end_at' => Carbon::parse($this->unavailability['end_at'])->format('H:i'),
                'id' => $this->unavailability['id'],
            ]
        ]);
    }

    public function delete()
    {
        $this->dispatch('open_modal', [
            'modal' => 'modals::unavailabilities.delete',
            'params' => $this->unavailability
        ]);
    }
};
?>

<div>
    <li class="pb-8 border-b border-b-black flex flex-col gap-4 sm:flex-row sm:justify-between sm:gap-8 sm:items-center">
        <div class="flex text-2xl flex-row sm:flex-col sm:items-center">
            <p>{{ Carbon::parse($this->unavailability['start_at'])->format('H\hi') }}</p>
            <span class="sm:hidden">&nbsp;-&nbsp;</span>
            <p>{{ Carbon::parse($this->unavailability['end_at'])->format('H\hi') }}</p>
        </div>

        <div class="relative rounded-2xl text-black w-full p-4 flex flex-col sm:flex-row sm:items-center gap-2">
            <div class="absolute left-0 top-0 h-full w-1 bg-error rounded-l-2xl"></div>
            <p class="flex-1">
                Créneau indisponible
            </p>
            @if(!$this->isReadOnly && (!$this->test->user->isAdmin || auth()->user()->isAdmin))
                <div class="flex gap-4 items-center">
                    <button class="cursor-pointer hover:scale-120 duration-200"
                            wire:click="edit({{ $this->unavailability['id'] }})">
                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier"
                             class="w-7 h-7">
                    </button>
                    <button class="cursor-pointer hover:scale-120 duration-200"
                            wire:click="delete({{ $this->unavailability['id'] }})">
                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Annuler"
                             class="w-6 h-6">
                    </button>
                </div>
            @endif
        </div>
    </li>
</div>
