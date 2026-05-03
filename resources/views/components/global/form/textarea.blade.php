@props(['name', 'isRequired' => true, 'type' => 'text', 'placeholder' => '', 'value' => '', 'rows' => '5'])

<div class="flex flex-col gap-4">
    <label for="{{ $name }}">
        {{ $slot }} <span class="text-error">{{ $isRequired ? '*' : '' }}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <textarea {{ $attributes }} class="border-2 resize-none border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none" rows="{{ $rows }}" id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }} placeholder="{{ $placeholder }}">{{ @old($name) ?? $value }}</textarea>
</div>
