<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function App\Helpers\generateSlots;

class AppointmentController
{
    public function services()
    {
        $services = Service::all();
        $selectedServices = session('appointment.services');

        return view('pages.client.appointment.appointment', compact('services', 'selectedServices'));
    }

    public function servicesStore(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        session([
            'appointment.services' => $validated['services'],
        ]);

        return redirect(route('appointment2', ['date' => today()->format('Y-m-d')]));
    }

    public function date(Request $request)
    {
        $services = Service::find(session('appointment.services'));

        if (! $services || $services->isEmpty()) {
            return redirect(route('appointment'));
        }

        $totalDuration = $services->sum('duration');

        $dateValue = $request->date
            ?? session('appointment.date')
            ?? today()->format('Y-m-d');

        $currentDate = Carbon::parse($dateValue)->startOfDay();

        if ($currentDate->lt(today()->startOfDay())) {
            $currentDate = today();
        }

        $currentMonth = $currentDate->copy()->startOfMonth();

        $startOfGrid = $currentMonth->copy()->startOfWeek(Carbon::MONDAY);

        $days = [];
        $cursor = $startOfGrid->copy();

        for ($i = 0; $i < 42; $i++) {
            $days[] = $cursor->copy();
            $cursor->addDay();
        }

        $slots = generateSlots($currentDate, $totalDuration);

        $availableDays = collect($days)->mapWithKeys(function ($day) use ($totalDuration) {
            $slots = generateSlots($day, $totalDuration);
            return [$day->format('Y-m-d') => count($slots) > 0];
        })->toArray();

        return view('pages.client.appointment.appointment2', compact(
            'slots',
            'services',
            'totalDuration',
            'dateValue',
            'currentDate',
            'currentMonth',
            'days',
            'availableDays'
        ));
    }

    public function dateStore(Request $request)
    {
        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                Rule::date()->afterOrEqual(today()),
            ],
            'slot' => 'required',
        ]);

        session([
            'appointment.date' => $validated['date'],
            'appointment.slot' => $validated['slot'],
        ]);

        return redirect(route('appointment3'));
    }

    public function confirmation()
    {
        if (
            ! session('appointment.services') ||
            ! session('appointment.date') ||
            ! session('appointment.slot')
        ) {
            return redirect(route('appointment2'));
        }

        $services = Service::find(session('appointment.services'));
        $start_at = Carbon::parse(
            session('appointment.date').' '.session('appointment.slot')
        )->locale('fr');

        $totalDuration = $services->sum('duration');

        return view(
            'pages.client.appointment.appointment3',
            compact(
                'services',
                'totalDuration',
                'start_at'
            )
        );
    }

    public function confirmationStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'telephone' => 'required',
            'message' => 'nullable',
        ]);

        $client = Client::where('email', $validated['email'])->first();

        if (! $client) {
            $client = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
            ]);
        }

        $services = Service::find(session('appointment.services'));
        $totalDuration = $services->sum('duration');

        $start = Carbon::parse(
            session('appointment.date').' '.session('appointment.slot')
        );

        $end = $start->copy()->addMinutes($totalDuration);

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'message' => $validated['message'],
            'start_at' => $start,
            'end_at' => $end,
        ]);

        $appointment->services()->attach($services->pluck('id'));

        session(['confirmed_appointment_id' => $appointment->id]);
        session()->forget('appointment');

        return redirect(route('thanks', $appointment));
    }
}
