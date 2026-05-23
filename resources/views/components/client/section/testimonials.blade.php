@php
    $items = [
        [
            'person' => 'Alexandre B.',
            'content' => 'J’ai enfin trouvé une coiffeuse qui comprend vraiment mes attentes. Dès le début, Joana a pris le temps de m’écouter et de me conseiller selon la nature de mes cheveux et la forme de mon visage. Le résultat est impeccable, bien au-delà de ce que j’espérais. En plus, l’ambiance est super agréable, on se sent vraiment à l’aise.'
        ],
        [
            'person' => 'Dimitri S.',
            'content' => 'Expérience parfaite du début à la fin. La prise de rendez-vous est simple, l’accueil chaleureux et le salon très propre. Joana est très professionnelle, elle explique chaque étape et donne de vrais conseils personnalisés. Je suis ressorti(e) avec une coupe moderne et facile à entretenir au quotidien.'
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
