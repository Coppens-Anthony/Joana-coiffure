<?php

use App\Jobs\ProcessUploadedPicture;
use App\Models\Photo;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $picture;

    public function store()
    {
        $validated = $this->validate([
            'picture' => 'required|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        logger()->info('UPLOAD', [
            'disk' => config('filesystems.default'),
            'path' => $full_path_to_original,
            'exists' => Storage::disk(config('filesystems.default'))->exists($full_path_to_original),
        ]);

        if ($this->picture) {
            $new_original_file_name = uniqid() . '.' . config('pictures.picture_type');

            $full_path_to_original = $this->picture->storeAs(
                config('pictures.original_path'),
                $new_original_file_name,
                config('filesystems.default')
            );

            if ($full_path_to_original) {
                $validated['picture'] = $new_original_file_name;
                ProcessUploadedPicture::dispatchSync($full_path_to_original, $new_original_file_name);
            } else {
                $validated['picture'] = '';
            }
        }

        Photo::create([
            'picture' => $validated['picture'],
            'position' => (Photo::max('position') ?? -1) + 1,
        ]);

        $this->reset('picture');

        $this->dispatch('action_done', message: 'Photo ajoutée avec succès !');
        $this->dispatch('close_modal');

    }
};
?>

<livewire:admin.modal modal_title="Ajouter une photo">
    <form wire:submit="store">
        <div class="flex w-full flex-col mx-auto gap-2 md:w-fit text-center font-bold">
            <input id="picture" name="picture" type="file" wire:model="picture"
                   class="invisible absolute top-0 left-0 h-0 w-0"
                   accept="image/*">
            <label for="picture"
                   class="w-full md:w-[33vw] h-75 bg-tertiary hover:bg-tertiary-2 duration-200 hover:duration-200 relative rounded-2xl cursor-pointer border-dashed border border-black">
                <div
                    class="w-full h-fit flex flex-col gap-8 items-center m-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="w-16 h-16 mx-auto">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M12 9.5V15.5M12 9.5L10 11.5M12 9.5L14 11.5M8.4 19C5.41766 19 3 16.6044 3 13.6493C3 11.2001 4.8 8.9375 7.5 8.5C8.34694 6.48637 10.3514 5 12.6893 5C15.684 5 18.1317 7.32251 18.3 10.25C19.8893 10.9449 21 12.6503 21 14.4969C21 16.9839 18.9853 19 16.5 19L8.4 19Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                    <p class="mx-auto">Sélectionnez une photo</p>
                </div>
                @if($this->picture instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                    <img src="{{$this->picture->temporaryUrl()}}" alt="{!! __('admin/table.image_alt') !!}"
                         class="object-contain absolute w-[33vw] h-75 rounded-2xl">
                @endif
            </label>
        </div>
        @error('picture')
        <small class="text-error mx-auto w-fit block my-2">
            {{ $message }}
        </small>
        @enderror
        <div class="ml-auto w-fit flex gap-6 mt-8">
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
