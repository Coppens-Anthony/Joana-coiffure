@props(['title', 'isSecondary' => false, 'route', 'class' => ''])

<a href="{{$route}}" title="{{$title}}"
        class="px-8 py-4 duration-200 block w-fit rounded-full cursor-pointer {{ $class }}
        {{ $isSecondary ? 'bg-white border-2 border-primary hover:bg-primary' : 'bg-primary border-2 border-primary hover:bg-white' }}">
    {{$slot}}
</a>
