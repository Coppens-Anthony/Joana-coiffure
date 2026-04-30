@php
    $items = [
        [
         'icon_path' => 'assets/svg/stroke_scissor.svg',
         'icon_alt' => '',
         'title' => 'Homme',
         'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation '
        ],
        [
         'icon_path' => 'assets/svg/comb.svg',
         'icon_alt' => '',
         'title' => 'Mèches',
         'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation '
        ],
        [
         'icon_path' => 'assets/svg/permanente.svg',
         'icon_alt' => '',
         'title' => 'Permanente',
         'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation '
        ],
];
@endphp


<x-client.layout title="Coiffeuse indépendante à Orp-Jauche">
    <x-client.section.icon_content :isTitle="false" :items="$items" :isLink="true" link_button_label="Toutes mes prestations"
                                   link_button_title="Voir toutes mes prestations" :link_button_route="route('prices')">
        Mes prestations
    </x-client.section.icon_content>
    <x-client.section.text_content
        img_path="assets/img/me.png"
        img_alt=""
        title="Qui suis-je ?"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation "
        :isLink="true"
        link_button_title="Vers la page à propos"
        link_button_label="En savoir plus sur moi"
        :link_button_route="route('about')"
    />
</x-client.layout>
