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
        img_alt="Portrait de Joana Monteiro"
        itemtype="https://schema.org/Person"
        content="Maman de plusieurs petits enfants (qui pourraient venir passer une tête de temps en temps lors du rendez-vous), je mets tout en avant afin que le client soit roi. Je conseille et adapte mes services en fonction du client afin de lui proposer le meilleur pour lui.">
        <span itemprop="name">Joana Monteiro</span>, <span itemprop="jobTitle">coiffeuse et visagiste</span>
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="room.jpg"
        img_alt="Image montrant l'espace coiffure avec le siège devant un miroir."
        :isReverse="true"
        content="Mon petit coin coiffure est très cocooning. Je voulais vraiment faire en sorte que le client soit directement à l'aise, qu'il se sente comme chez lui. J'ai alors aloué une petite pièce de chez moi que j'ai transformer en mini salon de coiffure. J'y ai placé un lavabo pour les lavages de cheveux et les colorations. ">
        Mon espace coiffure
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="school.jpg"
        img_alt="Image de l'école fréquentée."
        content="J’ai réalisé mes études en coiffure à l’École d’Ixelles, où j’ai suivi une formation de 5 ans me permettant d’obtenir également le titre de visagiste, ainsi que ma gestion. J’ai ensuite travaillé pendant 13 ans en salon de coiffure. Par la suite, j’ai choisi de devenir indépendante afin de développer une relation plus authentique avec mes clients.">
        Mon parcours
    </x-client.section.text_content>

    <x-client.section.approach/>
    <x-client.single_element.icon_content :items="$items">
        Mes valeurs
    </x-client.single_element.icon_content>
</x-client.layout>
