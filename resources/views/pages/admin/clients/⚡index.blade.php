<?php

use App\Models\Client;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Clients')]
class extends Component {
    public string $term = '';

    #[On('client_created')]
    public function refresh(string $message = '')
    {
        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[Computed]
    public function clients()
    {
        return Client::withCount('appointments')
            ->when($this->term, function ($query) {
                $query->where('name', 'like', '%' . $this->term . '%');
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::clients.create']);
    }
};
?>

<div>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <section class="flex flex-col gap-8">
        <h3 class="sr-only">Tableau des clients</h3>
        <div class="flex flex-col md:flex-row md:justify-between items-start md:items-end gap-4 md:gap-0">
            <form class="w-full md:w-fit">
                <x-global.form.input name="name"
                                     placeholder="John"
                                     type="search"
                                     wire:model.live.debounce="term"
                                     :isRequired="false">
                    Rechercher un client
                </x-global.form.input>
            </form>
            <x-global.linkButton.button_link title="Ajouter un client" wire:click="create">
                + Ajouter un client
            </x-global.linkButton.button_link>
        </div>
        <x-global.table :titles="['Nom', 'Email', 'Téléphone', 'Nombre de rendre-vous']">
            @if(count($this->clients) > 0)
                @foreach($this->clients as $client)
                    <tr onclick="Livewire.navigate('{{ route('clients.⚡show', $client->id) }}')"
                        class="table__tr hovered">
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
                            {{ $client->appointments_count }}
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
