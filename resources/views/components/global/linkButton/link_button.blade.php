@props(['title', 'isSecondary' => false, 'route', 'class' => ''])

<a href="{{$route}}" title="{{$title}}"
        class="px-8 py-4 duration-200 block w-fit rounded-full cursor-pointer focus:outline-none focus:ring-0 {{ $class }}
        {{ $isSecondary ? 'bg-secondary hover:bg-secondary-2 focus:bg-secondary-2' : 'bg-primary hover:bg-primary-2 focus:bg-primary-2' }}">
    {{$slot}}
</a>
