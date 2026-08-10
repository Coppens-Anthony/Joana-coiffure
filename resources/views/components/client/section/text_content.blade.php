@props(['isReverse' => false, 'img_path', 'img_alt', 'content', 'content2' => null, 'isLink' => false, 'link_button_title' => '', 'link_button_route' => '', 'link_button_label' => '', 'itemtype' => ''])
<section
    class="flex flex-col-reverse {{ $isReverse ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-8 md:gap-16">
    <img src="{{ asset('assets/img/originals/' . $img_path) }}"
         srcset="
         {{ asset('assets/img/variants/300x300/' . $img_path) }} 300w,
         {{ asset('assets/img/variants/600x600/' . $img_path) }} 600w,
         {{ asset('assets/img/variants/900x900/' . $img_path) }} 900w"
         sizes="(max-width: 768px) 100vw, 50vw"
         alt="{{ $img_alt }}"
         class="w-full md:w-1/2 rounded-[3rem]">
    <div class="flex flex-col gap-4 md:gap-8 md:w-1/2"
         @if($itemtype)
             itemtype="{{ $itemtype }}"
         itemscope
        @endif>
        <h2 class="text-[2rem]">{{ $slot }}</h2>
        @if($content2)
            <div class="flex flex-col gap-2">
                <p>{{ $content }}</p>
                <p>{{ $content2 }}</p>
            </div>
        @else
            <p>{{ $content }}</p>
        @endif
        @if($isLink)
            <x-global.link-button.link-button title="{{ $link_button_title }}"
                                              route="{{ $link_button_route }} ">{{ $link_button_label }}</x-global.link-button.link-button>
        @endif
    </div>
</section>
