@props(['title', 'isSecondary' => false, 'route'])

<a href="{{$route}}" title="{{$title}}"
        class="px-8 py-2 duration-200 block w-fit rounded-full cursor-pointer
        {{ $isSecondary ? 'bg-secondary hover:bg-secondary-2' : 'bg-primary hover:bg-primary-2' }}">
    {{$slot}}
</a>
