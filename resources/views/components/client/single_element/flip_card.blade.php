@props(['title', 'content'])

<label role="button" aria-label="Accéder à la description de l'étape : {{ $title }}" tabindex="0" class="bg-transparent w-full md:w-75 2xl:w-100 2xl:h-100 h-75 perspective-[1000px] block cursor-pointer group focus:outline-none focus:ring-0">
    <input type="checkbox" class="hidden peer">

    <div class="relative w-full h-full text-center transition-transform duration-600 transform-3d group-hover:transform-[rotateY(180deg)] group-focus:transform-[rotateY(180deg)] peer-checked:transform-[rotateY(180deg)]">
        <img src="{{ asset('assets/svg/flip.svg') }}" alt="" class="absolute top-4 right-4 w-8 h-8 z-50">

        <div class="absolute rounded-2xl w-full h-full bg-black text-white backface-hidden flex items-center justify-center">
            <p class="p-8 text-[2rem]">{{ $title }}</p>
        </div>

        <div class="absolute rounded-2xl w-full h-full bg-black text-white backface-hidden transform-[rotateY(180deg)] flex items-center justify-center bg-[url('../../public/assets/img/originals/flip1.jpg')] bg-no-repeat bg-cover">
            <p class="p-8 2xl:text-[1.5rem]">{{ $content }}</p>
        </div>
    </div>
</label>
