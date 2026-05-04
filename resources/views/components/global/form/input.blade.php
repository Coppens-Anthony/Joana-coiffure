@props(['name', 'isRequired' => true, 'type' => 'text', 'placeholder' => '', 'value' => ''])

<div class="flex flex-col gap-2">
    <label for="{{$name}}">
        {{$slot}} <span class="text-error">{{$isRequired ? '*' : ''}}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <input class="border-2 w-full border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none"
           type="{{ $type }}"
           id="{{$name}}"
           name="{{$name}}"
           {{ $isRequired ? 'required' : '' }}
           placeholder="{{ $placeholder }}"
           value="{{@old($name) ?? $value}}"
           {{ $attributes }}>
</div>
