<?php

use App\Jobs\ProcessUploadedPicture;
use App\Models\Photo;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $picture;

    #[Computed]
    public function photos()
    {
        return Photo::all();
    }

    public function store()
    {
        $validated = $this->validate([
            'picture' => 'required|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($this->picture) {
            $new_original_file_name = uniqid() . '.' . config('pictures.picture_type');

            $full_path_to_original = Storage::disk('public')
                ->putFileAs(config('pictures.original_path'),
                    $validated['picture'],
                    $new_original_file_name
                );

            if ($full_path_to_original) {
                $validated['picture'] = $new_original_file_name;
                ProcessUploadedPicture::dispatchSync($full_path_to_original, $new_original_file_name);
            } else {
                $validated['picture'] = '';
            }
        }

        Photo::create($validated);
    }
};
?>

<div>
    <form wire:submit="store">
        <input type="file" name="picture" id="picture" wire:model="picture" accept="image/*">
        <button type="submit">oui</button>
    </form>

</div>
