@props(['type' => 'submit', 'title', 'isSecondary' => false])

<button type="{{$type}}" title="{{$title}}"
        class="px-8 py-4 duration-200 w-fit rounded-full cursor-pointer
        {{ $isSecondary ? 'bg-secondary hover:bg-secondary-2' : 'bg-primary hover:bg-primary-2' }}">
    {{$slot}}
</button>
