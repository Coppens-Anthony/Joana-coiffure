@props(['title', 'type' => 'submit', 'class' => '', 'tabindex' => '0'])

<button type="{{ $type }}" tabindex="{{ $tabindex }}" title="{{ $title }}" class="relative group cursor-pointer focus:outline-none focus:ring-0 {{ $class }}" {{ $attributes }}>
    {{ $slot }}
    <span class="absolute left-0 top-full w-full h-4 flex items-center pointer-events-none">
            <img src="{{ asset('assets/svg/scissor.svg') }}" width="24" height="24" class="h-fit w-auto shrink-0
                        transition-opacity duration-100 delay-200
                        group-hover:delay-0 group-focus:delay-0
                        opacity-0 group-hover:opacity-100 group-focus:opacity-100" aria-hidden="true" alt="">
            <span class="flex-1 h-0.5 bg-black origin-left transition-transform duration-200
                         group-hover:delay-100 group-focus:delay-100
                         scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100" aria-hidden="true">
            </span>
        </span>
</button>
