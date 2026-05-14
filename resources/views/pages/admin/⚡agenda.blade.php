<?php

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Agenda')]
class extends Component {

    #[Computed]
    public function appointments()
    {
        return Appointment::with('client')
            ->get()
            ->map(fn($appointment) => [
                'title' => $appointment->client->name,
                'start' => $appointment->start_at
                    ->timezone('Europe/Brussels')
                    ->format('Y-m-d H:i:s'),
                'end' => $appointment->end_at
                    ->timezone('Europe/Brussels')
                    ->format('Y-m-d H:i:s'),
            ]);
    }
};
?>
<div>
    <div id="calendar" data-events='@json($this->appointments)'></div>
</div>
