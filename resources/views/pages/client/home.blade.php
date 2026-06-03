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
    <x-client.single_element.icon_content :isTitle="false" :items="$items" :isLink="true"
                                          link_button_label="Toutes mes prestations"
                                          link_button_title="Vers la page des tarifs"
                                          :link_button_route="route('prices')">
        Mes prestations
    </x-client.single_element.icon_content>
    <x-client.section.text_content
        img_path="me.jpg"
        img_alt=""
        content="Je m'appelle Joana Monteiro, je suis coiffeuse et visagiste depuis 2013. Je me suis mise en tant qu'indépendante afin d'aller chercher un vrai contact avec le client. Je fais en sorte que mon client soit mon centre d'intérêt, je ne veux pas simplement avoir des clients à la chaîne."
        :isLink="true"
        link_button_title="Vers la page à propos"
        link_button_label="En savoir plus sur moi"
        :link_button_route="route('about')">
        Qui suis-je ?
    </x-client.section.text_content>
    <x-client.section.testimonials/>
</x-client.layout>
