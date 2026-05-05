@props(['name', 'isRequired' => false, 'class' => '', 'options' => []])

<div class="flex flex-col gap-2 {{ $class }}">
    <label for="{{$name}}">
        {{$slot}} <span class="text-error">{{$isRequired ? '*' : ''}}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <select name="{{ $name }}" {{ $attributes }} id="{{ $name }}" class="border-2 border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none cursor-pointer">
        @foreach($options as $key => $value)
            <option value="{{ $key }}" class="cursor-pointer">{{ $value }}</option>
        @endforeach
    </select>
</div>
