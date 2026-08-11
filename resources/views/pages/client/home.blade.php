@php
    $items = [
        [
         'icon_path' => 'assets/svg/stroke_scissor.svg',
         'icon_alt' => '',
         'title' => $man->name,
         'desc' => $man->desc
        ],
        [
         'icon_path' => 'assets/svg/comb.svg',
         'icon_alt' => '',
         'title' => $meches->name,
         'desc' => $meches->desc
        ],
        [
         'icon_path' => 'assets/svg/permanente.svg',
         'icon_alt' => '',
         'title' => $permanente->name,
         'desc' => $permanente->desc
        ],
];
@endphp

<x-client.layout title="Coiffeuse indépendante à Orp-Jauche" :isContactOrAppointment="false">
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <x-client.single_element.icon_content :isTitle="false" :items="$items" :isLink="true"
                                          link_button_label="Toutes nos prestations"
                                          link_button_title="Vers la page des tarifs"
                                          :link_button_route="route('prices')">
        Nos prestations
    </x-client.single_element.icon_content>
    <x-client.section.text_content
        img_path="salon.jpg"
        img_alt="Portrait de Joana Monteiro"
        content="Notre salon vous accueille dans une ambiance chaleureuse et conviviale, avec une équipe à l’écoute de vos envies. Chaque prestation est réalisée avec attention afin de vous proposer un résultat qui correspond à votre style, vos habitudes et votre personnalité."
        :isLink="true"
        link_button_title="Vers la page à propos"
        link_button_label="En savoir plus le salon"
        :link_button_route="route('about')">
        Un salon à votre écoute
    </x-client.section.text_content>
    <x-client.section.testimonials/>
</x-client.layout>
