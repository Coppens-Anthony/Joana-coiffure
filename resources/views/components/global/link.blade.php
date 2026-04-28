@props(['route', 'title', 'isActive' => false])

<a href="{{ $route }}"
   title="{{ $title }}"
   class="relative group">
    {{ $slot }}
    <span class="absolute left-0 top-full w-full h-4 flex items-center pointer-events-none">
        <img src="{{ asset('assets/svg/scissor.svg') }}" class="h-fit w-auto flex-shrink-0
                    transition-opacity duration-100 delay-200
                    group-hover:delay-0
                    {{ $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}" alt="">
        <span class="flex-1 h-0.5 bg-black origin-left transition-transform duration-200
                     group-hover:delay-100
                     {{ $isActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}">
        </span>
    </span>
</a>
