<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Votre profil')]
class extends Component {
    public string $name;
    public string $email;
    public string $oldPassword;
    public string $password;
    public bool $monday;
    public bool $tuesday;
    public bool $wednesday;
    public bool $thursday;
    public bool $friday;
    public bool $saturday;
    public bool $sunday;

    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }

    public function mount()
    {
        $this->name = $this->authUser->name;
        $this->email = $this->authUser->email;

        $recurring = RecurringUnavailability::first();
        $days = $recurring->days_of_week ?? [];

        $this->monday = in_array(1, $days);
        $this->tuesday = in_array(2, $days);
        $this->wednesday = in_array(3, $days);
        $this->thursday = in_array(4, $days);
        $this->friday = in_array(5, $days);
        $this->saturday = in_array(6, $days);
        $this->sunday = in_array(0, $days);
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->authUser->id,
            'oldPassword' => 'required|min:8',
            'password' => 'nullable|min:8|different:oldPassword',
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
        ]);

        if (!Hash::check($validated['oldPassword'], $this->authUser->password)) {
            $this->addError('oldPassword', 'Mot de passe incorrect');
            return;
        }

        $this->authUser->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $this->authUser->password
        ]);

        $days = array_keys(array_filter([
            0 => $this->sunday,
            1 => $this->monday,
            2 => $this->tuesday,
            3 => $this->wednesday,
            4 => $this->thursday,
            5 => $this->friday,
            6 => $this->saturday,
        ]));

        $appointments = Appointment::where('start_at', '>=', now())
            ->get()
            ->filter(fn($appointment) => in_array(
                $appointment->start_at->dayOfWeek,
                $days
            ));

        if ($appointments->isNotEmpty()) {
            $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.confirmation', 'params' => ['appointment_ids' => $appointments->pluck('id')->toArray(), 'days' => $days]]);
        } else {
            $recurring = RecurringUnavailability::first();

            if ($recurring) {
                $recurring->update([
                    'days_of_week' => $days,
                    'starts_on' => now()
                ]);
            } else {
                RecurringUnavailability::create([
                    'days_of_week' => $days,
                    'starts_on' => now(),
                    'start_time' => '09:00',
                    'end_time' => '18:00',
                ]);
            }

            return redirect(route('profile'))
                ->with('success', 'Profil modifié avec succès');
        }
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
        </div>
        <fieldset class="flex flex-col gap-4 mt-4">
            <legend>Jours de congés récurrents</legend>
            <div class="grid grid-cols-2 gap-4  mt-2">
                <x-global.form.checkbox name="monday" wire:model="monday">
                    Lundi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="tuesday" wire:model="tuesday">
                    Mardi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="wednesday" wire:model="wednesday">
                    Mercredi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="thursday" wire:model="thursday">
                    Jeudi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="friday" wire:model="friday">
                    Vendredi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="saturday" wire:model="saturday">
                    Samedi
                </x-global.form.checkbox>
                <x-global.form.checkbox name="sunday" wire:model="sunday">
                    Dimanche
                </x-global.form.checkbox>
            </div>
        </fieldset>
        <x-global.linkButton.button title="Enregistrer les modifications" class="mx-auto block mt-8">Enregistrer
        </x-global.linkButton.button>
    </form>
</div>
