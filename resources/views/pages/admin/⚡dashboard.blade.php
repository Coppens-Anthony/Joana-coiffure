<?php

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')]
class extends Component {

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::with('client:id,name')->whereDate('start_at', today())->orderBy('start_at')->get();
    }
};
?>

<div>
    @if(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <section>
        <h2 class="text-2xl">Les rendez-vous de la journée</h2>
        @if($this->appointments->count() <= 0)
            <p class="mt-8">Il n'y a pas de rendez-vous aujourd'hui.</p>
        @endif
        <ol class="mt-6 flex flex-col gap-8">
            @foreach($this->appointments as $appointment)
                <livewire:admin.appointment.item_line :appointment="$appointment"/>
            @endforeach
        </ol>
    </section>
</div>
