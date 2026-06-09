<form action="{{ route('logout') }}" method="post" class="flex gap-2 items-center pb-4 border-black border-b">
    @csrf
    <img src="{{ asset('assets/svg/logout.svg') }}" alt="Se déconnecter" class="w-8 h-8">
    <x-global.link-button.button-link title="Se déconnecter" tabindex="1">
        Se déconnecter
    </x-global.link-button.button-link>
</form>
