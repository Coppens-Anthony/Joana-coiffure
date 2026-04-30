@php
    $items = [
        [
         'icon_path' => 'assets/svg/ear.svg',
         'icon_alt' => '',
         'title' => 'L\'écoute',
         'desc' => 'Comprendre réellement les attentes du client est essentiel. Une bonne coiffeuse prend le temps d’écouter pour proposer un résultat adapté et satisfaisant.'
        ],
        [
         'icon_path' => 'assets/svg/passion.svg',
         'icon_alt' => '',
         'title' => 'La passion',
         'desc' => 'La coiffure est un métier de créativité et d’engagement. La passion se ressent dans chaque geste et fait toute la différence dans le résultat final.'
        ],
        [
         'icon_path' => 'assets/svg/precision.svg',
         'icon_alt' => '',
         'title' => 'La précision',
         'desc' => 'Chaque détail compte : coupe, couleur, finition. Rien n’est laissé au hasard pour un rendu maîtrisé et harmonieux.'
        ],
];
@endphp

<x-client.layout title="À propos de moi">
    <x-client.section.text_content
        img_path="assets/img/about.png"
        img_alt=""
        title="Joana Monteiro, coiffeuse et visagiste"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation "
    />
    <x-client.section.text_content
        img_path="assets/img/room.png"
        img_alt=""
        title="Mon espace coiffure"
        :isReverse="true"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation "
    />
    <x-client.section.text_content
        img_path="assets/img/school.png"
        img_alt=""
        title="Mon parcours"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation "
    />

    <x-client.section.approach/>
    <x-client.section.icon_content :items="$items">
        Mes valeurs
    </x-client.section.icon_content>
</x-client.layout>
