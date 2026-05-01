<form action="{{ route('logout') }}" method="post" class="flex gap-2 items-center pb-4 mb-4 border-black border-b">
    @csrf
    <img src="{{ asset('assets/svg/email.svg') }}" alt="" class="w-8 h-8">
    <button title="Se déconnecter" class="relative group cursor-pointer">
        Se déconnecter
        <span class="absolute left-0 top-full w-full h-4 flex items-center pointer-events-none">
            <img src="{{ asset('assets/svg/scissor.svg') }}" class="h-fit w-auto shrink-0
                        transition-opacity duration-100 delay-200
                        group-hover:delay-0
                        opacity-0 group-hover:opacity-100" alt="">
            <span class="flex-1 h-0.5 bg-black origin-left transition-transform duration-200
                         group-hover:delay-100
                         scale-x-0 group-hover:scale-x-100">
            </span>
        </span>
    </button>
</form>
