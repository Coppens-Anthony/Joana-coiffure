<?php

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Unavailabilities;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $start_date;
    public string $end_date;
    public bool $isFullDay = false;
    public bool $isMultipleDays = false;
    public ?string $start_at = null;
    public ?string $end_at = null;
    public bool $contactClient = true;

    public function mount(array $params)
    {
        if (isset($params['start_date'], $params['end_date'])) {
            $this->start_date = $params['start_date'];
            $this->end_date = $params['end_date'];
            $this->isMultipleDays = true;
        } else {
            $this->start_date = $params['date'];
            $this->end_date = $params['date'];
            $this->isMultipleDays = false;
        }
    }

    #[Computed]
    public function conflictingAppointments()
    {
        if (!$this->isMultipleDays && !$this->isFullDay && ($this->start_at === null || $this->end_at === null)) {
            return collect();
        }

        if (!$this->isMultipleDays && !$this->isFullDay && $this->start_at >= $this->end_at) {
            return collect();
        }

        $start = $this->isMultipleDays
            ? $this->start_date . ' 09:00:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '09:00:00' : $this->start_at);

        $end = $this->isMultipleDays
            ? $this->end_date . ' 18:00:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '18:00:00' : $this->end_at);

        return Appointment::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_at', [$start, $end])
                ->orWhereBetween('end_at', [$start, $end])
                ->orWhere(fn($q) => $q->where('start_at', '<=', $start)->where('end_at', '>=', $end));
        })->get();
    }

    public function store()
    {
        $validated = $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'isFullDay' => $this->isMultipleDays ? 'sometimes' : 'boolean',
            'start_at' => $this->isMultipleDays ? 'sometimes' : 'nullable|required_if:isFullDay,false|date_format:H:i',
            'end_at' => $this->isMultipleDays ? 'sometimes' : 'nullable|required_if:isFullDay,false|date_format:H:i|after:start_at',
        ]);

        if ($this->conflictingAppointments->isNotEmpty()) {
            foreach ($this->conflictingAppointments as $appointment) {
                if ($this->contactClient) {
                    // ENVOIE DE MAIL
                }
                AppointmentService::where('appointment_id', $appointment->id)->delete();

                $appointment->delete();
            }
        }

        $startAt = $this->isMultipleDays
            ? $this->start_date . ' 09:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '09:00' : $this->start_at);

        $endAt = $this->isMultipleDays
            ? $this->end_date . ' 18:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '18:00' : $this->end_at);

        Unavailabilities::create([
            'start_at' => $startAt,
            'end_at' => $endAt,
        ]);
        $this->dispatch('action_done', message: 'Période off ajoutée avec succès !');
        $this->dispatch('close_modal');
    }
};
?>

<div>
    <livewire:admin.modal modal_title="Ajout d'une période off">
        <form wire:submit="store">
            @if($this->isMultipleDays)
                <div class="flex gap-4 my-8">
                    <x-global.form.input class="w-full" type="date" name="start" wire:model.live="start_date">
                        Date de début
                    </x-global.form.input>
                    <x-global.form.input class="w-full" type="date" name="end" wire:model.live="end_date">
                        Date de fin
                    </x-global.form.input>
                </div>
            @else
                <x-global.form.checkbox name="isFullDay" wire:model.live="isFullDay">
                    Journée entière off
                </x-global.form.checkbox>
                @if(!$this->isFullDay)
                    <div class="flex gap-4 my-8">
                        <x-global.form.input class="w-full" type="time" name="start_at" wire:model.live="start_at">
                            Heure de début
                        </x-global.form.input>
                        <x-global.form.input class="w-full" type="time" name="end_at" wire:model.live="end_at">
                            Heure de fin
                        </x-global.form.input>
                    </div>
                @endif
            @endif
            @if(($this->isMultipleDays || $this->isFullDay || ($this->start_at != null && $this->end_at != null)) && $this->conflictingAppointments->isNotEmpty())
                <div class="my-8">
                    <p class="mb-8"><span class="text-error">Attention</span>, il y
                        a {{ $this->conflictingAppointments->count() }}
                        rendez-vous durant cette période. Si vous
                        confirmez, {{ $this->conflictingAppointments->count() > 1 ? 'ils seront annulés' : 'il sera annulé'}}
                        .</p>
                    <x-global.form.checkbox name="contactClient" wire:model.live="contactClient">Prévenir les clients par
                        mail
                    </x-global.form.checkbox>
                </div>
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
