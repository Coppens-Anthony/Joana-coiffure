<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use function PHPUnit\Framework\isEmpty;

new class extends Component {
    public RecurringUnavailability $reccuring_unavailability;
    public string $start_at;
    public string $end_at;
    public bool $monday = false;
    public bool $tuesday = false;
    public bool $wednesday = false;
    public bool $thursday = false;
    public bool $friday = false;
    public bool $saturday = false;
    public bool $sunday = false;
    public ?string $model_id = null;
    public Collection $conflictingAppointments;

    public function mount(?string $model_id)
    {
        $this->start_at = config('app.hours.hour_start');
        $this->end_at = config('app.hours.hour_end');

        if ($model_id) {
            $this->reccuring_unavailability = RecurringUnavailability::findOrFail($model_id);
            $this->start_at = Carbon::parse($this->reccuring_unavailability->start_time)->format('H:i');
            $this->end_at = Carbon::parse($this->reccuring_unavailability->end_time)->format('H:i');
            $this->monday = in_array(1, $this->reccuring_unavailability->days_of_week);
            $this->tuesday = in_array(2, $this->reccuring_unavailability->days_of_week);
            $this->wednesday = in_array(3, $this->reccuring_unavailability->days_of_week);
            $this->thursday = in_array(4, $this->reccuring_unavailability->days_of_week);
            $this->friday = in_array(5, $this->reccuring_unavailability->days_of_week);
            $this->saturday = in_array(6, $this->reccuring_unavailability->days_of_week);
            $this->sunday = in_array(0, $this->reccuring_unavailability->days_of_week);
        }
    }

    public function days()
    {
        return [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];
    }

    public function appointments(string $start_at, string $end_at, array $days)
    {
        return Appointment::where('start_at', '>=', now())
            ->get()
            ->filter(function ($appointment) use ($days, $start_at, $end_at) {

                $futureDays = in_array($appointment->start_at->dayOfWeek, $days);

                $overlap =
                    $appointment->start_at->format('H:i') < $end_at
                    && $appointment->end_at->format('H:i') > $start_at;

                return $futureDays && $overlap;
            });
    }

    public function store()
    {
        $validated = $this->validate([
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i',
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


        if (empty($days)) {
            $this->dispatch('action_done', message: 'Aucun jour n\'a été selectionnée.', isDeleted: true);
            $this->dispatch('close_modal');

        } else {

            $this->conflictingAppointments = $this->appointments(
                $validated['start_at'],
                $validated['end_at'],
                $days
            );

            if ($this->conflictingAppointments->isNotEmpty()) {
                $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.confirmation', 'params' => ['appointment_ids' => $this->conflictingAppointments->pluck('id')->toArray(), 'days' => $days, 'start_at' => $validated['start_at'], 'end_at' => $validated['end_at']]]);
            } else {
                RecurringUnavailability::create([
                    'days_of_week' => $days,
                    'starts_on' => now(),
                    'start_time' => $validated['start_at'],
                    'end_time' => $validated['end_at'],
                ]);

                $this->dispatch('action_done', message: 'Congé récucrent ajouté avec succès !');
                $this->dispatch('close_modal');
            }
        }
    }

    public function update()
    {
        $validated = $this->validate([
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i',
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

        $this->conflictingAppointments = $this->appointments(
            $validated['start_at'],
            $validated['end_at'],
            $days
        );

        if ($this->conflictingAppointments->isNotEmpty()) {
            $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.confirmation', 'params' => ['appointment_ids' => $this->conflictingAppointments->pluck('id')->toArray(), 'days' => $days, 'start_at' => $validated['start_at'], 'end_at' => $validated['end_at'], 'reccuring_unavailabilityId' => $this->reccuring_unavailability->id]]);
        } else {
            $this->reccuring_unavailability->update([
                'days_of_week' => $days,
                'starts_on' => now(),
                'start_time' => $validated['start_at'],
                'end_time' => $validated['end_at'],
            ]);

            $this->dispatch('action_done', message: 'Congé récurrent modifié avec succès !');
            $this->dispatch('close_modal');
        }
    }
};
?>

<livewire:admin.modal
    modal_title="{{ $model_id ? 'Modification d’un congé récurrent' : 'Ajout d’un congé récurrent' }}">
    <form wire:submit="{{ $model_id ? 'update' : 'store' }}">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-3">
            @foreach($this->days() as $id => $value)
                <label class="day-toggle cursor-pointer">
                    <input type="checkbox"
                           id="{{ $id }}"
                           name="{{ $id }}"
                           wire:model.live="{{ $id }}"
                           class="sr-only peer">
                    <span class="flex flex-col items-center gap-2 p-4 rounded-xl transition-all duration-200 select-none text-center bg-secondary
                         peer-checked:bg-error peer-checked:text-white hover:opacity-70">
                <span>{{ $value }}</span>
            </span>
                </label>
            @endforeach
        </div>
        <div class="flex gap-8 w-full mt-4">
            <x-global.form.input class="w-full" name="start_at" wire:model.live="start_at" type="time">
                Heure de début
            </x-global.form.input>
            <x-global.form.input class="w-full" name="end_at" wire:model.live="end_at" type="time">
                Heure de fin
            </x-global.form.input>
        </div>

        <div class="ml-auto w-fit flex gap-6 mt-8">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                         wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button
                title="{{ $model_id ? 'Enregistrer' : 'Ajouter' }}">
                {{ $model_id ? 'Enregistrer' : 'Ajouter' }}
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
