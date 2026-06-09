<form action="{{ route('contact.store') }}" method="post" class="md:w-1/2 flex flex-col gap-4" aria-label="Formulaire de contact">
    @csrf
    <x-global.form.input name="name" placeholder="John Doe">
        Nom
    </x-global.form.input>
    <x-global.form.input name="email" type="email" placeholder="john@doe.com">
        Email
    </x-global.form.input>
    <x-global.form.input name="telephone" type="tel" placeholder="0123 45 67 89">
        Téléphone
    </x-global.form.input>
    <x-global.form.textarea name="message">
        Message
    </x-global.form.textarea>
    <small class="text-[.875rem] mb-8">
        <span class="text-error" aria-hidden="true">*</span>
        Champs obligatoires
    </small>
    <x-global.link-button.button title="Envoyer les informations de contact">Envoyer</x-global.link-button.button>
</form>
