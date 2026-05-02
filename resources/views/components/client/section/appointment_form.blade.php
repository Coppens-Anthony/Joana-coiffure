<section class="md:w-1/2">
    <h2 class="text-[2rem] mb-8">Finalisez le rendez-vous</h2>
    <form action="" method="post" class="flex flex-col gap-4">
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
        <x-global.form.textarea name="message" :isRequired="false">
            Informations supplémentaires <small class="text-[O.75rem]">(ex : allergies, précisions sur vos cheveux,
                etc)</small>
        </x-global.form.textarea>
        <small class="text-[0.75rem] mb-8">
            <span class="text-error">*</span>
            Champs obligatoires
        </small>
        <x-global.linkbutton.button title="Envoyer les informations de contact">Envoyer</x-global.linkbutton.button>
    </form>
</section>
