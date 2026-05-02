<?php

use App\Models\Client;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Clients')]
class extends Component {
    public string $term = '';

    #[Computed]
    public function clients()
    {
        return Client::when($this->term, function ($query) {
            $query->where('name', 'like', '%' . $this->term . '%');
        })
            ->orderBy('name')
            ->paginate(5);
    }
};
?>

<div>
    <section class="flex flex-col gap-8">
        <h3 class="sr-only">Tableau des clients</h3>
        <form class="w-fit">
            <x-global.form.input name="name"
                                 placeholder="John"
                                 type="search"
                                 wire:model.live.debounce="term"
                                 :isRequired="false">
                Rechercher un client
            </x-global.form.input>
        </form>
        <x-global.table :titles="['Nom', 'Email', 'Téléphone', 'Nombre de rendre-vous']">
            @if(count($this->clients) > 0)
                @foreach($this->clients as $client)
                <tr class="table__tr">
                    <td class="text_td">
                        <span class="title_td">Nom</span>
                        {{$client->name}}
                    </td>
                    <td class="text_td">
                        <span class="title_td">Email</span>
                        {{ $client->email }}
                    </td>
                    <td class="text_td">
                        <span class="title_td">Téléphone</span>
                        {{ $client->telephone }}
                    </td>
                    <td class="text_td">
                        <span class="title_td">Nombre de rendez-vous</span>
                        0
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td class="py-2" colspan="4">Aucun résultat</td>
                </tr>
            @endif
        </x-global.table>
        {{ $this->clients->links() }}
    </section>
</div>
