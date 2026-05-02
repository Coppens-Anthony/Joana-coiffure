<form action="{{ route('logout') }}" method="post" class="flex gap-2 items-center pb-4 mb-4 border-black border-b">
    @csrf
    <img src="{{ asset('assets/svg/logout.svg') }}" alt="" class="w-8 h-8">
    <x-global.linkbutton.button_link title="Se déconnecter">
        Se déconnecter
    </x-global.linkbutton.button_link>
</form>
