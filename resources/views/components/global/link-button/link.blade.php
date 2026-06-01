@props(['route', 'title', 'isActive' => false, 'class' => '', 'isTarget' => false])

<a href="{{ $route }}"
   wire:navigate
   {{ $attributes }}
   title="{{ $title }}"

   @if($isTarget) target="_blank" @endif
   class="relative group {{ $class }} focus:outline-none focus:ring-0">
    {{ $slot }}
    <span class="absolute left-0 top-full w-full h-4 flex items-center pointer-events-none">
        <img src="{{ asset('assets/svg/scissor.svg') }}" class="h-fit w-auto shrink-0
                    transition-opacity duration-100 delay-200
                    group-hover:delay-0 group-focus:delay-0
                    {{ $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-100 group-focus:opacity-100' }}" alt="">
        <span class="flex-1 h-0.5 bg-black origin-left transition-transform duration-200
                     group-hover:delay-100 group-focus:delay-100
                     {{ $isActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100 group-focus:scale-x-100' }}">
        </span>
    </span>
</a>
