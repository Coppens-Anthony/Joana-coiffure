<?php

use App\Mails\CanceledAppointment;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Unavailability;
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
    public ?int $unavailability_id = null;

    public function mount(array $params)
    {
        if (isset($params['start_date'], $params['end_date'])) {
            $this->start_date = $params['start_date'];
            $this->end_date = $params['end_date'];
            $this->isMultipleDays = $params['start_date'] !== $params['end_date'];
            $this->start_at = $params['start_at'] ?? null;
            $this->end_at = $params['end_at'] ?? null;
            $this->isFullDay = $this->start_at === '09:00' && $this->end_at === '18:00';
            $this->unavailability_id = $params['id'] ?? null;
        } else {
            $this->start_date = $params['date'];
            $this->end_date = $params['date'];
            $this->isMultipleDays = false;
        }
    }

    public function updatedStartDate()
    {
        if (empty($this->end_date) || $this->end_date < $this->start_date) {
            $this->end_date = $this->start_date;
        }
        $this->isMultipleDays = $this->start_date !== $this->end_date;
    }

    public function updatedEndDate(): void
    {
        if (empty($this->end_date)) {
            $this->end_date = $this->start_date;
        }
        $this->isMultipleDays = $this->end_date !== $this->start_date;
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
            ? $this->start_date . ' 09:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '09:00' : $this->start_at);

        $end = $this->isMultipleDays
            ? $this->end_date . ' 18:00'
            : $this->start_date . ' ' . ($this->isFullDay ? '18:00' : $this->end_at);

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
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'isFullDay' => $this->isMultipleDays ? 'sometimes' : 'boolean',
            'start_at' => $this->isMultipleDays ? 'sometimes' : 'nullable|required_if:isFullDay,false|date_format:H:i',
            'end_at' => $this->isMultipleDays ? 'sometimes' : 'nullable|required_if:isFullDay,false|date_format:H:i|after:start_at',
        ]);

        if ($this->conflictingAppointments->isNotEmpty()) {
            foreach ($this->conflictingAppointments as $appointment) {
                if ($this->contactClient) {
                    Mail::to(config('mail.from.address'))->send(
                        new CanceledAppointment($appointment)
                    );
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
            : $this->end_date . ' ' . ($this->isFullDay ? '18:00' : $this->end_at);

        $overlapping = Unavailability::where(function ($query) use ($startAt, $endAt) {
            $query->whereBetween('start_at', [$startAt, $endAt])
                ->orWhereBetween('end_at', [$startAt, $endAt])
                ->orWhere(fn($q) => $q->where('start_at', '<=', $startAt)->where('end_at', '>=', $endAt));
        })
            ->when($this->unavailability_id, fn($q) => $q->where('id', '!=', $this->unavailability_id))
            ->get();

        $finalStart = $overlapping->isNotEmpty()
            ? min($startAt, $overlapping->min('start_at'))
            : $startAt;

        $finalEnd = $overlapping->isNotEmpty()
            ? max($endAt, $overlapping->max('end_at'))
            : $endAt;

        $overlapping->each->delete();

        if ($this->unavailability_id) {
            Unavailability::find($this->unavailability_id)->update([
                'start_at' => $finalStart,
                'end_at' => $finalEnd,
            ]);
        } else {
            Unavailability::create([
                'start_at' => $finalStart,
                'end_at' => $finalEnd,
            ]);
        }

        $this->dispatch('action_done', message: 'Période off ajoutée avec succès !');
        $this->dispatch('close_modal');
    }
};
?>

<div>
    <livewire:admin.modal modal_title="Ajout d'une période off">
        <form wire:submit="store">
            <div class="flex gap-4 my-8">
                <x-global.form.input class="w-full" type="date" name="start_date" wire:model.live="start_date">
                    Date de début
                </x-global.form.input>
                <x-global.form.input class="w-full" type="date" name="end_date" wire:model.live="end_date">
                    Date de fin
                </x-global.form.input>
            </div>
            @if(!$this->isMultipleDays)
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
                    <x-global.form.checkbox name="contactClient" wire:model.live="contactClient">Prévenir les clients
                        par
                        mail
                    </x-global.form.checkbox>
                </div>
            @endif
            <div class="ml-auto w-fit flex gap-6">
                <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                             wire:click="dispatch('close_modal')">
                    Annuler
                </x-global.link-button.button>
                <x-global.link-button.button title="Confirmer l'ajoute de la période off">
                    Confirmer
                </x-global.link-button.button>
            </div>
        </form>
    </livewire:admin.modal>
</div>
