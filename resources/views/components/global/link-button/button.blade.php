@props(['type' => 'submit', 'title', 'isSecondary' => false, 'class' => ''])

<button type="{{$type}}" title="{{$title}}" {{ $attributes }}
        class="px-8 py-4 duration-200 w-fit rounded-full cursor-pointer {{ $class }} focus:outline-none focus:ring-0
        {{ $isSecondary ? 'bg-secondary border-2 border-secondary hover:bg-white focus:bg-white' : 'bg-primary border-2 border-primary hover:bg-white  focus:bg-white' }}">
    {{$slot}}
</button>
