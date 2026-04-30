@props(['title', 'content'])

<label class="bg-transparent w-full md:w-75 h-75 perspective-[1000px] block cursor-pointer group">
    <input type="checkbox" class="hidden peer" />

    <div class="relative w-full h-full text-center transition-transform duration-600 transform-3d group-hover:transform-[rotateY(180deg)] peer-checked:transform-[rotateY(180deg)]">

        <div class="absolute rounded-2xl w-full h-full bg-black text-white backface-hidden flex items-center justify-center">
            <p class="p-8 text-[2rem]">{{ $title }}</p>
        </div>

        <div class="absolute rounded-2xl w-full h-full bg-black text-white backface-hidden transform-[rotateY(180deg)] flex items-center justify-center bg-[url('../../public/assets/img/flip1.png')]">
            <p class="p-8">{{ $content }}</p>
        </div>

    </div>
</label>
