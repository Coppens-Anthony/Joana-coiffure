<?php

use App\Jobs\ProcessUploadedPicture;
use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new #[Title('Votre profil')]
class extends Component {
    use WithFileUploads;

    public string $name;
    public string $email;
    public string $oldPassword;
    public string $password;
    public string $color;
    public $avatar;

    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }

    public function mount()
    {
        $this->name = $this->authUser->name;
        $this->email = $this->authUser->email;
        $this->avatar = $this->authUser->avatar;
        $this->color = $this->authUser->color;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'color' => 'required|unique:users,color,' . $this->authUser->id,
            'email' => 'required|email|unique:users,email,' . $this->authUser->id,
            'oldPassword' => 'required|min:8',
            'password' => 'nullable|min:8|different:oldPassword',
            'avatar' => 'mimes:jpeg,png,jpg,gif,webp|max:2048' . $this->avatar ? 'nullable' : 'required',
        ]);

        if (!Hash::check($validated['oldPassword'], $this->authUser->password)) {
            $this->addError('oldPassword', 'Mot de passe incorrect');
            return;
        }

        if ($this->avatar instanceof TemporaryUploadedFile) {
            $new_original_file_name = uniqid() . '.' . config('pictures.picture_type');
            $disk = config('filesystems.default');

            $full_path_to_original = config('pictures.original_path') . '/' . $new_original_file_name;

            $full_path_to_original = $this->avatar->storeAs(
                config('pictures.original_path'),
                $new_original_file_name,
                $disk);

            if ($full_path_to_original) {
                $validated['avatar'] = $new_original_file_name;
                ProcessUploadedPicture::dispatchSync($full_path_to_original, $new_original_file_name);
            } else {
                $validated['avatar'] = $this->authUser->avatar ?? '';
            }
        }

        $this->authUser->update([
            'name' => $validated['name'],
            'avatar' => $validated['avatar'],
            'email' => $validated['email'],
            'color' => $validated['color'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $this->authUser->password
        ]);

        return redirect(route('profile'))
            ->with('success', 'Profil modifié avec succès');
    }
};
?>

<div>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form wire:submit="update">
        <div class="flex gap-8">
            <div class="flex flex-col gap-2 w-fit">
                <p>
                    Photo de profil
                    <small class="text-error">*</small>
                    @error('avatar')
                    <small class="text-error block">{{ $message }}</small>
                    @enderror
                </p>
                <input id="avatar" name="avatar" type="file" wire:model="avatar"
                       class="invisible absolute top-0 left-0 h-0 w-0"
                       accept="image/*">
                <label for="avatar"
                       class="w-56 h-full mx-auto relative rounded-2xl cursor-pointer hover:bg-primary duration-200 border-dashed border border-black">
                    @if($this->avatar instanceof TemporaryUploadedFile)
                        <img src="{{$this->avatar->temporaryUrl()}}" alt=""
                             class="object-cover absolute top-0 left-0 w-full h-full rounded-2xl">
                    @elseif(is_string($this->avatar) && $this->avatar !== '')
                        <img src="{{ Storage::url(config('pictures.original_path') . '/' . $this->avatar) }}" alt=""
                             class="object-cover absolute top-0 left-0 w-full h-full rounded-2xl">
                    @else
                        <img src="{{asset('assets/svg/profile.svg')}}"
                             alt=""
                             class="absolute top-1/2 left-1/2 -translate-1/2 origin-center">
                    @endif
                </label>
            </div>
            <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                <legende class="sr-only">Informations personnelles</legende>
                <x-global.form.input name="name" wire:model="name" :placeholder="$this->authUser->name"
                                     :value="$this->authUser->name">
                    Nom
                </x-global.form.input>
                <x-global.form.input name="email" wire:model="email" type="email" :placeholder="$this->authUser->email"
                                     :value="$this->authUser->email">
                    Email
                </x-global.form.input>
                <x-global.form.input name="oldPassword" wire:model="oldPassword" type="password">
                    Mot de passe actuel
                </x-global.form.input>
                <x-global.form.input name="password" wire:model="password" type="password" :isRequired="false">
                    Nouveau mot de passe
                </x-global.form.input>
            </fieldset>
        </div>
        <div class="flex items-center gap-2 mt-8" x-data="{ color: @entangle('color') }">
            <label for="color" class="relative cursor-pointer group">
                <div class="w-14 h-14 rounded-full border-4 border-white shadow-lg ring-1 ring-black/10
                     transition-transform group-hover:scale-105"
                     :style="{ backgroundColor: color }"></div>
                <input type="color" id="color" name="color"
                       class="sr-only"
                       x-model="color">
            </label>
            <div>
                <p>
                    Couleur personnelle
                    <small class="text-error">*</small>
                    @error('color')
                    <small class="text-error block">{{ $message }}</small>
                    @enderror
                </p>
                <span class="text-[.85rem]" x-text="color"></span>
            </div>
        </div>
        <x-global.link-button.button title="Enregistrer les modifications" class="mx-auto block mt-8">
            Enregistrer
        </x-global.link-button.button>
    </form>
</div>
