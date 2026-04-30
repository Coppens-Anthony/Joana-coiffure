@php
    $items = [
        [
            'person' => 'Alexandre B.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora!'
        ],
        [
            'person' => 'Dimitri S.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora!'
        ],
        [
            'person' => 'Bruno M.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora!'
        ],
        [
            'person' => 'Dylan P.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum delectus deleniti dignissimos modi nulla omnis qui recusandae sequi veniam voluptatum. Accusantium ad aperiam cumque dolore iste iure quasi quos tempora!'
        ]
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
