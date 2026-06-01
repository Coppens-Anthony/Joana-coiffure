@props(['isTitle' => true, 'items' => [], 'isLink' => false, 'link_button_title' => '', 'link_button_route' => '', 'link_button_label' => ''])

<section class="flex flex-col gap-8">
    <h2 class="{{ $isTitle ? 'text-[2rem] mb-16' : 'sr-only' }}">{{ $slot }}</h2>
    <ul class="flex flex-col gap-8 md:flex-row md:justify-between">
        @foreach($items as $item)
            <li class="flex flex-col text-center md:w-1/3">
                <article>
                    <img src="{{ asset($item['icon_path'] ) }}" alt="{{ $item['icon_alt'] }}" class="mx-auto w-32 h-32 mb-8">
                    <h3 class="text-[2rem] mb-4">{{ $item['title'] }}</h3>
                    <p>
                        {{ $item['desc'] }}
                    </p>
                </article>
            </li>
        @endforeach
    </ul>
    @if($isLink)
        <x-global.link-button.link-button class="mx-auto" :title="$link_button_title" :route="$link_button_route ">{{ $link_button_label }}</x-global.link-button.link-button>
    @endif
</section>
