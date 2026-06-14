<?php

use App\Jobs\ProcessUploadedPicture;
use App\Models\Photo;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public array $pictures = [];

    public function store()
    {
        $validated = $this->validate([
            'pictures' => 'required|array',
            'pictures.*' => 'mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        foreach ($validated['pictures'] as $picture) {
            if ($picture) {
                $new_original_file_name = uniqid() . '.' . config('pictures.picture_type');
                $disk = config('filesystems.default');

                $full_path_to_original = config('pictures.original_path') . '/' . $new_original_file_name;

                $full_path_to_original = $picture->storeAs(
                    config('pictures.original_path'),
                    $new_original_file_name,
                    $disk);

                if ($full_path_to_original) {
                    $picture = $new_original_file_name;
                    ProcessUploadedPicture::dispatchSync($full_path_to_original, $new_original_file_name);
                }
            }

            Photo::create([
                'picture' => $picture,
                'position' => (Photo::max('position') ?? -1) + 1,
            ]);
        }

        $this->reset('pictures');
        $this->dispatch('action_done', message: 'Photo ajoutée avec succès !');
        $this->dispatch('close_modal');
    }

    public function removePicture(int $index): void
    {
        array_splice($this->pictures, $index, 1);
    }
};
?>

<livewire:admin.modal modal_title="Ajouter une photo">
    <form wire:submit="store">
        <div class="flex w-full flex-col mx-auto gap-2 md:w-full max-h-92 text-center font-bold">
            <input id="pictures" name="pictures" multiple type="file" wire:model="pictures"
                   class="sr-only"
                   accept="image/*">

            <label for="pictures"
                   class="w-full bg-tertiary hover:bg-tertiary-2 duration-300 relative rounded-2xl cursor-pointer border-dashed border border-black flex items-center justify-center gap-4
              {{ count($this->pictures) ? 'h-20': 'h-75' }}">

                <div
                    class="{{ count($this->pictures) ? 'flex flex-col sm:flex-row items-center gap-2 md:gap-4' : 'flex flex-col gap-8 items-center' }}">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="{{ count($this->pictures) ? 'w-8 h-8' : 'w-16 h-16' }} duration-300 shrink-0"
                         aria-hidden="true">
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M12 9.5V15.5M12 9.5L10 11.5M12 9.5L14 11.5M8.4 19C5.41766 19 3 16.6044 3 13.6493C3 11.2001 4.8 8.9375 7.5 8.5C8.34694 6.48637 10.3514 5 12.6893 5C15.684 5 18.1317 7.32251 18.3 10.25C19.8893 10.9449 21 12.6503 21 14.4969C21 16.9839 18.9853 19 16.5 19L8.4 19Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                    <span>
            {{ count($this->pictures) ? 'Ajouter d\'autres photos' : 'Sélectionnez des photos' }}
        </span>
                </div>
            </label>

            @if(count($this->pictures))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2 w-full overflow-y-scroll no-scrollbar">
                    @foreach($this->pictures as $index => $picture)
                        <div class="relative">
                            <img src="{{ $picture->temporaryUrl() }}"
                                 class="object-cover w-full h-32 rounded-xl" alt="">
                            <button title="Supprimer la photo de la galerie"
                                    type="button"
                                    class="absolute top-2 right-2 cursor-pointer"
                                    wire:click="removePicture({{ $index }})">
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="white"
                                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path
                                        d="M22 5.00012L20.7987 21.0173C20.6935 22.4201 20.6408 23.1216 20.3 23.6535C19.9999 24.1217 19.5472 24.4981 19.0017 24.7332C18.382 25.0001 17.591 25.0001 16.0093 25.0001H9.99065C8.4089 25.0001 7.61803 25.0001 6.99833 24.7332C6.45275 24.4981 6.00008 24.1217 5.69998 23.6535C5.3591 23.1216 5.3065 22.4201 5.20129 21.0173L4 5.00012M1 5.00012H25M19 5.00012L18.5941 3.91755C18.2006 2.86846 18.0039 2.34391 17.6391 1.9561C17.3169 1.61363 16.9031 1.34855 16.4357 1.18516C15.9064 1.00012 15.2845 1.00012 14.0404 1.00012H11.9596C10.7155 1.00012 10.0936 1.00012 9.56426 1.18516C9.09688 1.34855 8.68312 1.61363 8.36094 1.9561C7.99608 2.34391 7.79938 2.86846 7.40596 3.91755L7 5.00012M16 10.3335V19.6668M10 10.3335V19.6668"
                                        stroke="#B92629" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @error('pictures')
        <small class="text-error mx-auto w-fit block my-2">
            {{ $message }}
        </small>
        @enderror
        <div class="ml-auto w-fit flex flex-col sm:flex-row items-start gap-6 mt-8">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                         wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button title="Ajouter la photo">
                Ajouter
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
