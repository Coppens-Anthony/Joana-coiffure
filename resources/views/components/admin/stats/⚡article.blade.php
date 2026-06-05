<?php

use Livewire\Component;

new class extends Component
{
    public string $title;
};
?>

<div>
    <article class="w-full h-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
        <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">{{ $this->title }}</h3>
        <div class="rounded-b-2xl bg-white my-6 mx-4">
            <ul class="flex flex-col gap-4">
                {{ $slot }}
            </ul>
        </div>
    </article>
</div>
