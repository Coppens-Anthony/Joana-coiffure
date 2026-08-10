@php
    $items = [
        [
            'person' => 'Alexandre B.',
            'content' => 'Cela fait maintenant plusieurs fois que je viens au salon et je suis toujours aussi satisfait. Le personnel prend le temps de comprendre ce que je souhaite et n’hésite pas à donner ses conseils lorsque j’en ai besoin. Le résultat est toujours soigné et correspond parfaitement à mes attentes. C’est devenu un vrai plaisir de venir me faire coiffer ici !'
        ],
        [
            'person' => 'Dimitri S.',
            'content' => 'Dès la première visite, je me suis senti très à l’aise. L’accueil est chaleureux, l’ambiance est agréable et surtout, on prend vraiment le temps de s’occuper de vous. J’apprécie particulièrement les conseils qui sont adaptés à mes cheveux et à ce que je recherche. Je ressors à chaque fois satisfait de ma coupe et avec l’impression d’avoir passé un vrai moment de détente.'
        ],
]
@endphp

<section class="p-8 bg-tertiary rounded-[3rem]">
    <h2 class="text-[2rem] mb-16">Quelques avis</h2>
    <ul class="grid grid-cols-1 md:grid-cols-2 gap-16">
        @foreach($items as $item)
            <li>
                <x-client.single_element.testimony :item="$item"/>
            </li>
        @endforeach
    </ul>
</section>
