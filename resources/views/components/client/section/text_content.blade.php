@props(['isReverse' => false, 'img_path', 'img_alt', 'content', 'isLink' => false, 'link_button_title' => '', 'link_button_route' => '', 'link_button_label' => '', 'itemtype' => ''])
<section
    class="flex flex-col-reverse {{ $isReverse ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-8 md:gap-16">
    <img src="{{ asset('assets/img/originals/' . $img_path) }}"
         srcset="
         {{ asset('assets/img/variants/300x300/' . $img_path) }} 300w,
         {{ asset('assets/img/variants/600x600/' . $img_path) }} 600w,
         {{ asset('assets/img/variants/900x900/' . $img_path) }} 900w"
         sizes="(max-width: 768px) 50vw, 20vw"
         alt="{{ $img_alt }}"
         class="w-full md:w-1/2 rounded-[3rem]">
    <div class="flex flex-col gap-4 md:gap-8 md:w-1/2">
        <h2 class="text-[2rem]" {{ $itemtype ? $itemtype . ' ' . 'itemscope' : ''}}>{{ $slot }}</h2>
        <p>{{ $content }}</p>
        @if($isLink)
            <x-global.linkbutton.link_button title="{{ $link_button_title }}"
                                             route="{{ $link_button_route }} ">{{ $link_button_label }}</x-global.linkbutton.link_button>
        @endif
    </div>
</section>
