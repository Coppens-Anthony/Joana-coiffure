@props(['type' => 'submit', 'title', 'isSecondary' => false, 'class' => '', 'isDangerous' => false])

<button type="{{$type}}" title="{{$title}}" {{ $attributes }}
class="px-8 py-4 duration-200 w-fit rounded-full cursor-pointer {{ $class }}
        {{ $isDangerous ? 'bg-error border-2 border-error text-white hover:bg-white hover:text-error' :  ($isSecondary ? 'bg-white border-2 border-primary hover:bg-primary ' : 'bg-primary border-2 border-primary hover:bg-white') }}">
    {{$slot}}
</button>
