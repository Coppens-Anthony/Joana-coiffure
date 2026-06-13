<?php

use App\Models\Photo;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $picture;

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }
    }

    #[On('updateOrder')]
    public function updateOrder($order)
    {
        foreach ($order as $item) {
            Photo::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }
    }

    #[Computed]
    public function photos()
    {
        return Photo::orderByDesc('position')->get();
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::pictures.create']);
    }

    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::pictures.delete', 'model_id' => $id]);
    }
};
?>

<div>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <section>
        <div class="flex flex-col md:flex-row justify-between gap-4 md:items-end my-8">
            <div class="flex flex-col gap-2">
                <h2 class="text-2xl">Prévisualisation de la galerie</h2>
                <p>Vous pouvez échanger les photos de place en les faisant glisser.</p>
            </div>
            <x-global.link-button.button-link class="w-fit" title="Ajouter une photo" wire:click="create">
                + Ajouter une photo
            </x-global.link-button.button-link>
        </div>
        @if($this->photos->isEmpty())
            <p>Aucune photo pour le moment dans la galerie. Ajoutez-en afin de les voir ici.</p>
        @else
            <ul class="grid grid-cols-2 md:grid-cols-4 gap-4" data-sortable>
                @foreach($this->photos as $photo)
                    <li data-id="{{ $photo->id }}" class="relative cursor-move">
                        <img src="{{ Storage::url('pictures/originals/' . $photo->picture) }}"
                             srcset="{{ Storage::url('pictures/variants/300x300/' . $photo->picture) }} 300w,
                                {{ Storage::url('pictures/variants/600x600/' . $photo->picture) }} 600w,
                                {{ Storage::url('pictures/variants/900x900/' . $photo->picture) }} 900w"
                             sizes="(max-width: 768px) 20vw, 25vw"
                             alt=""
                             class="h-full w-full object-cover rounded-2xl">
                        <button title="Supprimer la photo de la galerie"
                                class="absolute top-2 right-2 cursor-pointer" wire:click="delete({{ $photo->id }})">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="white"
                                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path
                                    d="M22 5.00012L20.7987 21.0173C20.6935 22.4201 20.6408 23.1216 20.3 23.6535C19.9999 24.1217 19.5472 24.4981 19.0017 24.7332C18.382 25.0001 17.591 25.0001 16.0093 25.0001H9.99065C8.4089 25.0001 7.61803 25.0001 6.99833 24.7332C6.45275 24.4981 6.00008 24.1217 5.69998 23.6535C5.3591 23.1216 5.3065 22.4201 5.20129 21.0173L4 5.00012M1 5.00012H25M19 5.00012L18.5941 3.91755C18.2006 2.86846 18.0039 2.34391 17.6391 1.9561C17.3169 1.61363 16.9031 1.34855 16.4357 1.18516C15.9064 1.00012 15.2845 1.00012 14.0404 1.00012H11.9596C10.7155 1.00012 10.0936 1.00012 9.56426 1.18516C9.09688 1.34855 8.68312 1.61363 8.36094 1.9561C7.99608 2.34391 7.79938 2.86846 7.40596 3.91755L7 5.00012M16 10.3335V19.6668M10 10.3335V19.6668"
                                    stroke="#B92629" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>
