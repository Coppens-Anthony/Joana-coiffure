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
        img_path="school.jpg"
        img_alt="Image de l'école fréquentée."
        content="Au fil des années, l'activité s'est développée, le salon a grandi et l'équipe s'est agrandie. Mais malgré cette évolution, l'esprit du salon est resté le même : prendre le temps de comprendre chaque client, partager notre passion du métier et proposer un service personnalisé.">
        Joana-Coiffure
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="me.jpg"
        img_alt="Portrait de Joana Monteiro"
        :isReverse="true"
        itemtype="https://schema.org/Person"
        content="Après plusieurs années à accompagner ses clientes en tant que coiffeuse indépendante, Joana a souhaité aller plus loin en créant un espace où chaque personne peut bénéficier d'un accompagnement personnalisé dans une ambiance chaleureuse et conviviale."
        content2="Aujourd'hui, elle met son expérience et son savoir-faire au service du salon tout en partageant ses valeurs avec son équipe.">
        Joana, la patronne
    </x-client.section.text_content>
    <x-client.section.text_content
        img_path="room.jpg"
        img_alt="Image montrant l'espace coiffure avec le siège devant un miroir."
        content="Chaque membre de notre équipe partage la même passion pour la coiffure et le même engagement : vous offrir une expérience agréable ainsi qu'un résultat qui vous ressemble."
        content2="Grâce à nos différentes expertises, nous vous accompagnons dans tous vos projets capillaires, des coupes du quotidien aux transformations plus audacieuses."
    >
        L'équipe du salon
    </x-client.section.text_content>
    <x-client.section.approach/>
    <x-client.single_element.icon_content :items="$items">
        Nos valeurs
    </x-client.single_element.icon_content>
</x-client.layout>
