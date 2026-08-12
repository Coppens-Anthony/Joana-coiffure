<?php

use App\Mails\ContactForm;
use App\Mails\EditAppointment;
use App\Mails\EditAppointmentRecap;
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
    public ?int $selected_user_id = null;
    public array $services_id = [];
    public array $appointmentSlots = [];
    public string $hour;
    public bool $hasServices = false;
    public Appointment $appointment;
    public bool $isEditing = false;

    public function mount(?string $model_id, array $params)
    {
        $this->selectedDate = $params['date'];
        if ($model_id) {
            $this->isEditing = true;
            $this->appointment = Appointment::where('uuid', $model_id)->firstOrFail();

            $this->client_id = $this->appointment->client_id;
            $this->selected_user_id = $this->appointment->user_id;
            $this->services_id = $this->appointment->services->pluck('id')->toArray();
            $this->hour = $this->appointment->start_at->format('H:i') . '-' . $this->appointment->end_at->format('H:i');

            $this->refreshSlots();
        }

        if (!auth()->user()->isAdmin()) {
            $this->selected_user_id = auth()->id();
        }
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
    public function users()
    {
        return User::where('isAdmin', false)
            ->orderBy('name')->pluck('name', 'id')
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

    public function refreshSlots()
    {
        $this->hasServices = true;

        if (empty($this->services_id) || !$this->selectedDate || !$this->selected_user_id) {
            $this->appointmentSlots = [];
            return;
        }

        $services = Service::whereIn('id', $this->services_id)->get();
        $totalDuration = $services->sum('duration');

        $date = Carbon::parse($this->selectedDate);

        $appointments = Appointment::where('user_id', $this->selected_user_id)
            ->whereDate('start_at', $date)
            ->when($this->isEditing, fn($q) => $q->where('id', '!=', $this->appointment->id))
            ->get();

        $adminId = User::where('isAdmin', true)->value('id');

        $unavailabilities = Unavailability::whereIn('user_id', [$adminId, $this->selected_user_id])
            ->where('start_at', '<=', $date->copy()->setTime(18, 0))
            ->where('end_at', '>=', $date->copy()->setTime(9, 0))
            ->get();

        $recurringRules = RecurringUnavailability::whereIn('user_id', [$adminId, $this->selected_user_id])
            ->where('starts_on', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>=', $date);
            })
            ->get();

        $this->appointmentSlots = collect(generateSlots($date, $totalDuration, $appointments, $unavailabilities, $recurringRules))
            ->mapWithKeys(fn($slot) => [$slot['start'] . '-' . $slot['end']
            => $slot['start'] . ' - ' . $slot['end']])
            ->toArray();
    }

    public function updatedServicesId()
    {
        $this->hour = '';
        $this->refreshSlots();
    }

    public function updatedSelectedUserId()
    {
        $this->hour = '';
        $this->refreshSlots();
    }

    public function updatedSelectedDate()
    {
        $this->hour = '';
        $this->refreshSlots();
    }

    public function store()
    {
        $validated = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'selected_user_id' => 'required|exists:users,id',
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
            'user_id' => $validated['selected_user_id']
        ]);

        foreach ($validated['services_id'] as $service) {
            AppointmentService::create([
                'appointment_id' => $appointment->id,
                'service_id' => $service
            ]);
        }

        $user = User::findOrFail($validated['selected_user_id']);

        Mail::to($user)->send(
            new NewAppointment($appointment)
        );

        Mail::to($appointment->client->email)->send(
            new NewAppointmentRecap($appointment)
        );

        $this->dispatch('action_done', message: 'Rendez-vous ajouté avec succès !', closeModal: false);
        $this->dispatch('close_modal');
    }

    public function update()
    {
        $validated = $this->validate([
            'selectedDate' => 'required|date|after_or_equal:' . today(),
            'client_id' => 'required|exists:clients,id',
            'selected_user_id' => 'required|exists:users,id',
            'services_id' => 'required|array',
            'services_id.*' => 'exists:services,id',
            'hour' => 'required|string|in:' . implode(',', array_keys($this->appointmentSlots)),
        ]);

        $hour = explode('-', $validated['hour']);
        $start_at = $this->selectedDate . ' ' . $hour[0];
        $end_at = $this->selectedDate . ' ' . $hour[1];

        $this->appointment->update([
            'client_id' => $validated['client_id'],
            'start_at' => $start_at,
            'end_at' => $end_at,
            'user_id' => $validated['selected_user_id']
        ]);

        $this->appointment->services()->sync($validated['services_id']);

        $user = User::findOrFail($validated['selected_user_id']);

        if (auth()->user()->isAdmin()) {
            Mail::to($user)->send(
                new EditAppointment($this->appointment)
            );
        }

        Mail::to($this->appointment->client->email)->send(
            new EditAppointmentRecap($this->appointment)
        );

        $this->dispatch('action_done', message: 'Rendez-vous modifié avec succès !', closeModal: false);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal :modal_title="$isEditing ? 'Modification du rendez-vous' : 'Ajout d\'un rendez-vous'">
    <form class="flex flex-col gap-4" wire:submit="{{$isEditing ? 'update' : 'store'}}">
        @if($isEditing)
            <x-global.form.input type="date" name="selectedDate" wire:model.live="selectedDate">
                Date
            </x-global.form.input>
        @endif
        <div class="{{ $this->isEditing && auth()->user()->isAdmin() ? 'flex flex-col md:flex-row gap-8' : ''}} ">
            <livewire:admin.searchable_field wire:model="client_id" label="Client" :items="$this->clients"
                                             wire:key="client_field" :isClientAdding="true"/>
            @if(auth()->user()->isAdmin())
                <livewire:admin.searchable_field wire:model.live="selected_user_id" label="Coiffeur"
                                                 :items="$this->users"
                                                 wire:key="user_field"/>
            @endif
        </div>
        <livewire:admin.multiple_field wire:model.live="services_id" label="Services" :items="$this->services"
                                       wire:key="services_field"/>

        @if(auth()->user()->isAdmin() && ! $this->selectedDate)
            <div class="flex flex-col gap-2">
                <p>Horaire <span class="text-error">*</span></p>
                <p class="border-2 border-primary p-4 rounded-2xl">
                    Veuillez d'abord choisir une date.
                </p>
            </div>
        @elseif(!$selected_user_id)
            <div class="flex flex-col gap-2">
                <p>Horaire <span class="text-error">*</span></p>
                <p class="border-2 border-primary p-4 rounded-2xl">
                    Veuillez d'abord choisir un coiffeur.
                </p>
            </div>
        @elseif (empty($this->services_id))
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

            <x-global.link-button.button :title=" $isEditing ? 'Modifier le rendez-vous' : 'Ajouter le rendez-vous'">
                {{ $isEditing ? 'Modifier' : 'Ajouter' }}
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
