<?php

use App\Mails\CanceledAppointment;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\RecurringUnavailability;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public array $appointmentIds = [];
    public array $days = [];
    public bool $contactClient = true;
    public string $start_at;
    public string $end_at;
    public ?string $reccuring_unavailabilityId = null;

    public function mount(array $params)
    {
        $this->appointmentIds = $params['appointment_ids'];
        $this->days = $params['days'];
        $this->start_at = $params['start_at'];
        $this->end_at = $params['end_at'];
        if (isset($params['reccuring_unavailabilityId'])) {
            $this->reccuring_unavailabilityId = $params['reccuring_unavailabilityId'];
        }
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::whereIn('id', $this->appointmentIds)->get();
    }

    public function store()
    {
        $validated = $this->validate([
            'contactClient' => 'boolean'
        ]);

        foreach ($this->appointmentIds as $appointmentId) {
            $appointment = Appointment::find($appointmentId);
            if ($this->contactClient) {
                Mail::send(
                    new CanceledAppointment($appointment)
                );
            }

            AppointmentService::where('appointment_id', $appointmentId)->delete();
            $appointment->delete();
        }

        if ($this->reccuring_unavailabilityId) {
            $recurring_unavailability = RecurringUnavailability::findOrFail($this->reccuring_unavailabilityId);
            $recurring_unavailability->update([
                'days_of_week' => $this->days,
                'starts_on' => now(),
                'start_time' => $this->start_at,
                'end_time' => $this->end_at,
            ]);
        } else {
            RecurringUnavailability::create([
                'days_of_week' => $this->days,
                'starts_on' => now(),
                'start_time' => $this->start_at,
                'end_time' => $this->end_at,
            ]);
        }

        return redirect(route('recurring_unavailabilities'))->with('success', 'Congé récrrent ajouté avec succès !');
    }
};
?>


<livewire:admin.modal modal_title="Confirmation">
    <form wire:submit="store">
        <div class="my-8">
            <p class="mb-8"><span class="text-error">Attention</span>, il y
                a {{ count($this->appointments) }}
                rendez-vous durant cette période. Si vous
                confirmez, {{ count($this->appointments) > 1 ? 'ils seront annulés' : 'il sera annulé'}}
                .</p>
            <x-global.form.checkbox name="contactClient" wire:model.live="contactClient">Prévenir les clients
                par
                mail
            </x-global.form.checkbox>
        </div>
        <div class="ml-auto w-fit flex gap-6">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                        wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button title="Confirmer la période off">
                Confirmer
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
