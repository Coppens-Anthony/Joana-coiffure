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

<x-client.layout title="À propos de moi" :isContactOrAppointment="false">
    <x-client.section.text_content
        img_path="about.jpg"
        img_alt=""
        itemtype="https://schema.org/Person"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ">
        <span itemprop="name">Joana Monteiro</span>, <span itemprop="jobTitle">coiffeuse et visagiste</span>
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="room.jpg"
        img_alt=""
        :isReverse="true"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ">
        Mon espace coiffure
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="school.jpg"
        img_alt=""

        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ">
        Mon parcours
    </x-client.section.text_content>

    <x-client.section.approach/>
    <x-client.single_element.icon_content :items="$items">
        Mes valeurs
    </x-client.single_element.icon_content>
</x-client.layout>
