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

    public function mount(array $params)
    {
        $this->appointmentIds = $params['appointment_ids'];
        $this->days = $params['days'];
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
                Mail::to(config('mail.from.address'))->send(
                    new CanceledAppointment($appointment)
                );
            }

            AppointmentService::where('appointment_id', $appointmentId)->delete();
            $appointment->delete();
        }

        $recurring = RecurringUnavailability::first();

        if ($recurring) {
            $recurring->update([
                'days_of_week' => $this->days,
                'starts_on' => now()
            ]);
        } else {
            RecurringUnavailability::create([
                'days_of_week' => $this->days,
                'starts_on' => now(),
                'start_time' => '09:00',
                'end_time' => '18:00',
            ]);
        }

        return redirect(route('profile'))
            ->with('success', 'Profil modifié avec succès');
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
