<section class="md:w-1/2">
    <h2 class="text-[2rem] mb-8">Finalisez le rendez-vous</h2>
    <form action="{{ route('appointment4.store') }}" method="post" class="flex flex-col gap-4">
        @csrf
        <x-global.form.input name="name" placeholder="John Doe" :value="session('appointment.client_name') ?? ''">
            Nom
        </x-global.form.input>
        <x-global.form.input name="email" type="email" placeholder="john@doe.com" :value="session('appointment.client_email') ?? ''">
            Email
        </x-global.form.input>
        <x-global.form.input name="telephone" type="tel" placeholder="0123 45 67 89" :value="session('appointment.client_telephone') ?? ''">
            Téléphone
        </x-global.form.input>
        <x-global.form.textarea name="message" :isRequired="false" :value="session('appointment.message') ?? ''">
            Informations supplémentaires <small class="text-[.875rem]">(ex : allergies, précisions sur vos cheveux,
                etc)</small>
        </x-global.form.textarea>
        <small class="text-[.875rem] mb-8">
            <span class="text-error">*</span>
            Champs obligatoires
        </small>
        <x-global.link-button.button title="Envoyer les informations de contact">Envoyer</x-global.link-button.button>
    </form>
</section>
