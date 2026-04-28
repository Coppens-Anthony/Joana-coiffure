@props(['name', 'isRequired' => false, 'options' => []])

<div class="flex flex-col gap-4">
    <label for="{{$name}}">
        {{$slot}} <span class="text-error">{{$isRequired ? '*' : ''}}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <select name="{{ $name }}" id="{{ $name }}" class="border-2 border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none">
        @foreach($options as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>
