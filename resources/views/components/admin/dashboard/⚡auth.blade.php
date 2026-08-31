<?php

use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

new class extends Component {
    public function getRevenueDataProperty(): array
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        return [
            'labels' => collect(range(1, 12))
                ->map(fn($m) => Carbon::create()->month($m)->translatedFormat('M'))
                ->toArray(),
            'previousYear' => $this->monthlyRevenue($previousYear, onlyPast: false),
            'currentYear' => $this->monthlyRevenue($currentYear, onlyPast: true),
        ];
    }

    private function monthlyRevenue(int $year, bool $onlyPast): array
    {
        $query = Appointment::query()
            ->whereYear('start_at', $year)
            ->with('services');

        if ($onlyPast) {
            $query->where('end_at', '<=', now());
        }

        $appointments = $query->get();

        $byMonth = $appointments
            ->groupBy(fn($appointment) => $appointment->start_at->month)
            ->map(fn($group) => $group->sum(fn($appointment) => $appointment->services->sum('price')));

        return collect(range(1, 12))
            ->map(fn($m) => (float)$byMonth->get($m, 0))
            ->toArray();
    }

    public function getStatsProperty(): array
    {
        return [
            [
                'icon' => asset('assets/svg/agenda.svg'),
                'route' => route('agenda'),
                'title' => 'Vers les rendez-vous',
                'value' => Appointment::whereMonth('start_at', now()->month)
                    ->whereYear('start_at', now()->year)
                    ->count(),
                'label' => 'Rendez-vous',
            ],
            [
                'icon' => asset('assets/svg/profile.svg'),
                'route' => route('members.index'),
                'title' => 'Vers les membres',
                'value' => User::where('isAdmin', false)->count(),
                'label' => 'Membres',
            ],
            [
                'icon' => asset('assets/svg/clients.svg'),
                'route' => route('clients.index'),
                'title' => 'Vers les clients',
                'value' => Client::count(),
                'label' => 'Clients',
            ],
            [
                'icon' => asset('assets/svg/scissor.svg'),
                'route' => route('database.services'),
                'title' => 'Vers les prestations',
                'value' => Service::count(),
                'label' => 'Prestations',
            ],
            [
                'icon' => asset('assets/svg/stats.svg'),
                'route' => route('statistics'),
                'title' => 'Vers les prestations',
                'value' => number_format($this->monthlyRevenueTotal(), 0, ',', ' ') . ' €',
                'label' => 'Revenus',
            ],
        ];
    }

    private function monthlyRevenueTotal(): float
    {
        return Appointment::whereMonth('start_at', now()->month)
            ->whereYear('start_at', now()->year)
            ->where('end_at', '<=', now())
            ->with('services')
            ->get()
            ->sum(fn($appointment) => $appointment->services->sum('price'));
    }
};
?>

<div>
    <section>
        <h2 class="text-2xl mb-8">Quelques chiffres du mois</h2>
        <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 2lg:flex md:justify-between gap-4">
            @foreach($this->stats as $stat)
                <li class="group relative overflow-hidden rounded-2xl p-5 flex flex-col justify-between min-w-35 transition-all duration-200 hover:-translate-y-1 bg-primary hover:shadow-lg hover:shadow-primary/20">
                    <a href="{{ $stat['route'] }}" title="{{ $stat['title'] }}" class="absolute inset-0 z-10"></a>

                    <img src="{{ $stat['icon'] }}" alt="" class="w-8 h-8 mb-4">

                    <div>
                        <p class="text-3xl">{{ $stat['value'] }}</p>
                        <p>{{ $stat['label'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
    <section>
        <h2 class="text-2xl mt-8 mb-4">Comparaison des revenus</h2>
        <div class="chart w-full">
            <canvas
                id="chart"
                class="w-full"
                data-labels="{{ json_encode($this->revenueData['labels']) }}"
                data-year1-label="{{ now()->subYear()->year }}"
                data-year1="{{ json_encode($this->revenueData['previousYear']) }}"
                data-year2-label="{{ now()->year }}"
                data-year2="{{ json_encode($this->revenueData['currentYear']) }}"
            ></canvas>
        </div>
    </section>
</div>
