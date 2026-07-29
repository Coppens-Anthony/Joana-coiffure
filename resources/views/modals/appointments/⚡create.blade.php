<?php

use App\Mails\ContactForm;
use App\Mails\NewAppointment;
use App\Mails\NewAppointmentRecap;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Client;
use App\Models\RecurringUnavailability;
use App\Models\Service;
use App\Models\Unavailability;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use function App\Helpers\generateSlots;

new class extends Component {
    public string $selectedDate;
    public int $client_id;
    public array $services_id = [];
    public array $appointmentSlots = [];
    public string $hour;
    public bool $hasServices = false;

    public function mount(array $params)
    {
        $this->selectedDate = $params['date'];
    }

    #[Computed]
    public function clients()
    {
        return Client::orderBy('name')->pluck('name', 'id')
            ->map(fn($name, $id) => ['id' => $id, 'label' => $name])
            ->values()
            ->toArray();
    }

    #[Computed]
    public function services()
    {
        return Service::orderBy('name')->pluck('name', 'id')
            ->map(fn($name, $id) => ['id' => $id, 'label' => $name])
            ->values()
            ->toArray();
    }

    public function updatedServicesId()
    {
        $this->hasServices = true;

        if (empty($this->services_id) || !$this->selectedDate) {
            $this->slots = [];
            return;
        }

        $services = Service::whereIn('id', $this->services_id)->get();
        $totalDuration = $services->sum('duration');

        $date = Carbon::parse($this->selectedDate);

        $appointments = Appointment::whereDate('start_at', $date)
            ->where('user_id', auth()->id())
            ->get();

        $unavailabilities = Unavailability::where('start_at', '<=', $date->copy()->setTime(18, 0))
            ->where('end_at', '>=', $date->copy()->setTime(9, 0))
            ->where('user_id', auth()->id())
            ->get();

        $user = User::where('isAdmin', true)->first();
        $reccuringRules = RecurringUnavailability::whereIn('user_id', [auth()->id(), $user->id])
            ->get();

        $this->appointmentSlots = collect(generateSlots($date, $totalDuration, $appointments, $unavailabilities, $reccuringRules))
            ->mapWithKeys(fn($appointmentSlot) => [
                $appointmentSlot['start'] . '-' . $appointmentSlot['end'] => $appointmentSlot['start'] . ' - ' . $appointmentSlot['end']
            ])
            ->toArray();
    }

    public function store()
    {
        $validated = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'services_id' => 'required|array',
            'services_id.*' => 'exists:services,id',
            'hour' => 'required|string|in:' . implode(',', array_keys($this->appointmentSlots)),
        ]);

        $hour = explode('-', $validated['hour']);
        $start_at = $this->selectedDate . ' ' . $hour[0];
        $end_at = $this->selectedDate . ' ' . $hour[1];

        $appointment = Appointment::create([
            'uuid' => Str::uuid(),
            'client_id' => $validated['client_id'],
            'start_at' => $start_at,
            'end_at' => $end_at,
            'user_id' => auth()->id()
        ]);

        foreach ($validated['services_id'] as $service) {
            AppointmentService::create([
                'appointment_id' => $appointment->id,
                'service_id' => $service
            ]);
        }

        $users = [
            config('mail.reply_to.address'),
            'joanacoiffure190@gmail.com',
        ];

        foreach ($users as $user) {
            Mail::to($user)->send(
                new NewAppointment($appointment)
            );
        }

        Mail::to($appointment->client->email)->send(
            new NewAppointmentRecap($appointment)
        );

        $this->dispatch('action_done', message: 'Rendez-vous ajouté avec succès !', closeModal: false);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Ajout d'un rendez-vous">
    <form class="flex flex-col gap-4" wire:submit="store">
        <livewire:admin.searchable_field wire:model="client_id" label="Client" :items="$this->clients"
                                         wire:key="client_field"/>
        <livewire:admin.multiple_field wire:model.live="services_id" label="Services" :items="$this->services"
                                       wire:key="services_field"/>
        @if (empty($services_id))
            <div class="flex flex-col gap-2">
                <p>Horaire <span class="text-error">*</span></p>
                <p class="border-2 border-primary p-4 rounded-2xl focus:border-primary focus:outline-none">
                    Veuillez d’abord choisir une/des prestations(s)
                </p>
            </div>

        @elseif ($this->hasServices && empty($this->appointmentSlots))
            <div class="flex flex-col gap-2">
                <p>Horaire <span class="text-error">*</span></p>
                <p class="border-2 border-primary p-4 rounded-2xl focus:border-primary focus:outline-none">
                    Aucun horaire disponible
                </p>
            </div>

        @else
            <x-global.form.select
                name="hour"
                wire:model="hour"
                :options="$this->appointmentSlots"
                :isRequired="true"
                :isDefaultOption="true"
            >
                Plage horaire
            </x-global.form.select>
        @endif

        <div class="ml-auto w-fit flex gap-6 mt-4">
            <x-global.link-button.button
                type="button"
                title="Fermer la modale"
                :isSecondary="true"
                wire:click="dispatch('close_modal')"
            >
                Annuler
            </x-global.link-button.button>

            <x-global.link-button.button title="Ajouter le rendez-vous">
                Ajouter
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
