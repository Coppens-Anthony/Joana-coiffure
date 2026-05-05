<?php

use App\Models\Appointment;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Statistiques')]
class extends Component {
    public int $month;
    public int $year;
    public array $monthOptions = [];
    public array $yearOptions = [];

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;

        $this->monthOptions = [0 => 'Tous'] + collect(range(1, 12))->mapWithKeys(function ($month) {
                return [
                    $month => ucfirst(now()->month($month)->translatedFormat('F'))
                ];
            })->toArray();

        $start = 2026;
        $current = now()->year;

        $this->yearOptions = collect(range($start, $current))
            ->mapWithKeys(fn($year) => [$year => $year])
            ->toArray();
    }

    private function baseAppointmentsQuery()
    {
        $query = Appointment::query()
            ->whereYear('updated_at', $this->year);

        if ($this->month !== 0) {
            $query->whereMonth('updated_at', $this->month);
        }

        return $query;
    }

    #[Computed]
    public function appointments()
    {
        return $this->baseAppointmentsQuery()
            ->with('services:id,price,name')
            ->get(['id', 'client_id']);
    }

    #[Computed]
    public function totalClients()
    {
        return $this->appointments
            ->pluck('client_id')
            ->unique();
    }

    #[Computed]
    public function recurringClients()
    {
        return $this->appointments
            ->groupBy('client_id')
            ->filter(fn($group) => $group->count() > 1);
    }

    #[Computed]
    public function newClients()
    {
        $query = Client::query()
            ->whereYear('created_at', $this->year);

        if ($this->month !== 0) {
            $query->whereMonth('created_at', $this->month);
        }

        return $query;
    }

    #[Computed]
    public function services()
    {
        return $this->appointments->flatMap->services;
    }

    #[Computed]
    public function totalRevenue()
    {
        return $this->appointments
            ->flatMap->services
            ->sum('price');
    }

    #[Computed]
    public function averageRevenue()
    {
        $count = $this->appointments->count();

        return $count > 0
            ? $this->totalRevenue / $count
            : 0;
    }

    #[Computed]
    public function mostRequestedService()
    {
        return $this->appointments
            ->flatMap->services
            ->groupBy('id')
            ->map(fn($group) => [
                'name' => $group->first()->name,
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->first();
    }

    public function download()
    {
        $file = Pdf::loadView('pdf.stats', [
            'appointments' => $this->appointments,
            'totalClients' => $this->totalClients,
            'recurringClients' => $this->recurringClients,
            'newClients' => $this->newClients,
            'totalRevenue' => $this->totalRevenue,
            'averageRevenue' => $this->averageRevenue,
            'mostRequestedService' => $this->mostRequestedService,
            'month' => $this->month,
            'year' => $this->year,
        ]);

        return response()->streamDownload(
            fn() => print($file->output()),
            'statistiques-' . (Carbon::create($this->year, $this->month)->locale(App::getLocale())->translatedFormat('F-Y')) . '.pdf'
        );
    }
}
?>

<div>
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 md:gap-0">
        <form class="flex gap-8 w-1/2">
            <x-global.form.select class="w-full" name="month" wire:model.live="month" :options="$this->monthOptions">
                Mois
            </x-global.form.select>
            <x-global.form.select class="w-full" name="month" wire:model.live="year" :options="$this->yearOptions">
                Année
            </x-global.form.select>
        </form>

        <x-global.linkButton.button_link class="flex gap-2 items-center" title="Télécharger le PDF" type="button"
                                         wire:click="download">
            Télécharger le PDF
            <img src="{{ asset('assets/svg/download.svg') }}" alt="">
        </x-global.linkButton.button_link>
    </div>
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-8">
        <h2 class="sr-only">Statistques pour le bilan</h2>
        <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
            <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Rendez-vous</h3>
            <div class="rounded-b-2xl bg-white my-6 mx-4">
                <ul class="flex flex-col gap-4">
                    <li>{{ $this->appointments->count() }} rendez-vous</li>
                </ul>
            </div>
        </article>
        <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
            <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Clients</h3>
            <div class="rounded-b-2xl bg-white my-6 mx-4">
                <ul class="flex flex-col gap-4">
                    <li>{{ $this->totalClients->count() }} clients</li>
                    <li>{{ $this->recurringClients->count() }} clients réccurents</li>
                    <li>{{ $this->newClients->count() }} nouveaux clients</li>
                </ul>
            </div>
        </article>
        <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
            <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Revenus</h3>
            <div class="rounded-b-2xl bg-white my-6 mx-4">
                <ul class="flex flex-col gap-4">
                    <li>{{ number_format($this->totalRevenue, 0, '', ' ') }}€ de revenu total</li>
                    <li>{{ number_format($this->averageRevenue, 2, ',', ' ') }}€ de revenu moyen par rendez-vous</li>
                </ul>
            </div>
        </article>
        <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
            <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Prestations</h3>
            <div class="rounded-b-2xl bg-white my-6 mx-4">
                <ul class="flex flex-col gap-4">
                    <li>
                        @if($this->mostRequestedService)
                            {{ $this->mostRequestedService['name'] }} est la prestation la plus demandée
                        @else
                            Aucune prestation durant cette période
                        @endif
                    </li>
                </ul>
            </div>
        </article>

    </section>
</div>
