<?php

use App\Models\Appointment;
use App\Models\Unavailabilities;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $date;
    public bool $isFullDay = false;
    public ?string $start_at = null;
    public ?string $end_at = null;
    public bool $contactClient = true;

    public function mount(array $params)
    {
        $this->date = $params['date'];
    }

    #[Computed]
    public function conflictingAppointments()
    {
        if (!$this->isFullDay && ($this->start_at === null || $this->end_at === null)) {
            return collect();
        }

        if (!$this->isFullDay && $this->start_at >= $this->end_at) {
            return collect();
        }

        $start = $this->date . ' ' . ($this->isFullDay ? '09:00:00' : $this->start_at);
        $end = $this->date . ' ' . ($this->isFullDay ? '18:00:00' : $this->end_at);

        return Appointment::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_at', [$start, $end])
                ->orWhereBetween('end_at', [$start, $end])
                ->orWhere(fn($q) => $q->where('start_at', '<=', $start)->where('end_at', '>=', $end));
        })->get();
    }

    public function store()
    {
        $validated = $this->validate([
            'isFullDay' => 'boolean',
            'start_at' => 'nullable|required_if:isFullDay,false|date_format:H:i',
            'end_at' => 'nullable|required_if:isFullDay,false|date_format:H:i|after:start_at',
            'contactClient' => 'boolean:strict'
        ]);

        if ($this->conflictingAppointments->isNotEmpty()) {
            foreach ($this->conflictingAppointments as $appointment) {
                if ($validated['contactClient']) {
                    // ENVOIE DE MAIL
                }
                $appointment->delete();
            }

            $this->dispatch('action_done', message: 'Période off ajoutée avec succès !');
            $this->dispatch('close_modal');
        } else {
            Unavailabilities::create([
                'start_at' => $this->date . ' ' . ($validated['isFullDay'] ? '09:00' : $validated['start_at']),
                'end_at' => $this->date . ' ' . ($validated['isFullDay'] ? '18:00' : $validated['end_at']),
            ]);
            $this->dispatch('action_done', message: 'Période off ajoutée avec succès !');
            $this->dispatch('close_modal');
        }
    }
};
?>

<div>
    <livewire:admin.modal modal_title="Ajout d'une période off">
        <form wire:submit="store">
            <x-global.form.checkbox name="isFullDay" wire:model.live="isFullDay">Journée entière off
            </x-global.form.checkbox>
            @if(!$this->isFullDay)
                <div class="flex flex-col md:flex-row gap-4 my-8">
                    <x-global.form.input type="time" class="w-full" name="start_at" wire:model.live="start_at">Heure de
                        début
                    </x-global.form.input>
                    <x-global.form.input type="time" class="w-full" name="end_at" wire:model.live="end_at">Heure de fin
                    </x-global.form.input>
                </div>
            @endif
            @if(($this->isFullDay || ($this->start_at != null && $this->end_at != null)) && $this->conflictingAppointments->isNotEmpty())
                <div class="mt-8">
                    <p class="mb-8"><span class="text-error">Attention</span>, il y a {{ $this->conflictingAppointments->count() }}
                        rendez-vous durant cette période. Si vous confirmez, {{ $this->conflictingAppointments->count() > 1 ? 'ils seront annulés' : 'il sera annulé'}}.</p>
                    <x-global.form.checkbox name="contactClient" wire:model="contactClient">Prévenir les clients par
                        mail
                    </x-global.form.checkbox>
                </div>
            @else

            @endif
            <div class="ml-auto w-fit flex gap-6">
                <x-global.linkButton.button type="button" title="Fermer la modale" :isSecondary="true"
                                            wire:click="dispatch('close_modal')">
                    Annuler
                </x-global.linkButton.button>
                <x-global.linkButton.button title="Ajouter la période off">
                    Ajouter
                </x-global.linkButton.button>
            </div>
        </form>
    </livewire:admin.modal>
</div>
