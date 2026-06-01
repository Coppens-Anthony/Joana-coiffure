@props(['title', 'isSecondary' => false, 'route', 'class' => ''])

<a href="{{$route}}" title="{{$title}}"
        class="px-8 py-4 duration-200 block w-fit rounded-full cursor-pointer focus:outline-none focus:ring-0 {{ $class }}
        {{ $isSecondary ? 'bg-secondary border-2 border-secondary hover:bg-white focus:bg-white' : 'bg-primary border-2 border-primary hover:bg-white  focus:bg-white' }}">
    {{$slot}}
</a>
