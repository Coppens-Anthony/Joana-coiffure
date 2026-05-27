<?php

use App\Mails\CanceledAppointment;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Note;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Appointment $appointment;
    public bool $contactClient = false;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->appointment = Appointment::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $validated = $this->validate([
            'contactClient' => 'required|boolean:strict'
        ]);

        if ($validated['contactClient']) {
            Mail::to(config('mail.from.address'))->send(
                new CanceledAppointment($this->appointment)
            );
        }

        AppointmentService::where('appointment_id', $this->appointment->id)->delete();

        $this->appointment->delete();

        $this->dispatch('action_done', message: 'Rendez-vous annulé avec succès !', isDeleted: true);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Annuler le rendez-vous">
    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir annuler le rendez-vous de {{ $appointment->client->name }} ?
    </p>

    <form wire:submit="destroy" class="flex flex-col gap-4">
        @csrf

        <x-global.form.checkbox name="contactClient" wire:model="contactClient">
            Prévenir le client via un mail ?
        </x-global.form.checkbox>
        <div class="ml-auto w-fit flex gap-6">
            <x-global.linkButton.button
                type="button"
                title="Fermer la modale"
                :isSecondary="true"
                wire:click="dispatch('close_modal')"
            >
                Annuler
            </x-global.linkButton.button>

            <x-global.linkButton.button title="Annuler le rendez-vous">
                Confirmer
            </x-global.linkButton.button>
        </div>
    </form>
</livewire:admin.modal>
