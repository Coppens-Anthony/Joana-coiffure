@props(['title', 'type' => 'submit', 'class' => ''])

<button type="{{ $type }}" title="{{ $title }}" class="relative group cursor-pointer {{ $class }}" {{ $attributes }}>
    {{ $slot }}
    <span class="absolute left-0 top-full w-full h-4 flex items-center pointer-events-none">
            <img src="{{ asset('assets/svg/scissor.svg') }}" class="h-fit w-auto shrink-0
                        transition-opacity duration-100 delay-200
                        group-hover:delay-0
                        opacity-0 group-hover:opacity-100" alt="">
            <span class="flex-1 h-0.5 bg-black origin-left transition-transform duration-200
                         group-hover:delay-100
                         scale-x-0 group-hover:scale-x-100">
            </span>
        </span>
</button>
