@props(['type' => 'submit', 'title', 'isSecondary' => false, 'class' => ''])

<button type="{{$type}}" title="{{$title}}" {{ $attributes }}
        class="px-8 py-4 duration-200 w-fit rounded-full cursor-pointer {{ $class }} focus:outline-none focus:ring-0
        {{ $isSecondary ? 'bg-secondary hover:bg-secondary-2 focus:bg-secondary-2' : 'bg-primary hover:bg-primary-2 focus:bg-primary-2' }}">
    {{$slot}}
</button>
