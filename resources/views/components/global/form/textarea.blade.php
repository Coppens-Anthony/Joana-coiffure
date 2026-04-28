@props(['name', 'isRequired' => false, 'type' => 'text', 'placeholder', 'value' => ''])

<div class="flex flex-col gap-4">
    <label for="{{ $name }}">
        {{ $slot }} <span class="text-error">{{ $isRequired ? '*' : '' }}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <textarea class="border-2 resize-none border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none" rows="5" id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }} placeholder="{{ $placeholder }}">{{ @old($name) ?? $value }}</textarea>
</div>
