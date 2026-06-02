@props(['name', 'isRequired' => true, 'type' => 'text', 'placeholder' => '', 'value' => '', 'class' => ''])

<div class="flex flex-col gap-2 {{ $class }} password-wrapper relative">
    <label for="{{$name}}">
        {{$slot}} <span class="text-error">{{$isRequired ? '*' : ''}}</span>
        @error($name)
        <small class="text-error">
            {{ $message }}
        </small>
        @enderror
    </label>
    <input class="border-2 w-full border-primary p-4 rounded-2xl focus:border-primary focus:outline-none"
           type="{{ $type }}"
           id="{{$name}}"
           name="{{$name}}"
           {{ $isRequired ? 'required' : '' }}
           placeholder="{{ $placeholder }}"
           value="{{@old($name) ?? $value}}"
        {{ $attributes }}>
    @if($type === 'password')
        <button type="button" class="toggle_password hidden js:block absolute bottom-3 right-4 w-8 h-8 cursor-pointer">
            <img src="{{ asset('assets/svg/eye.svg') }}" alt="Afficher le mote de passe">
        </button>
    @endif
</div>
