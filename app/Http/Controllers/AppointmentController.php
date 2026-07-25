<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        return redirect(route('appointment2'));
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

        $gridStart = $startOfGrid->copy()->startOfDay();
        $gridEnd = $startOfGrid->copy()->addDays(41)->endOfDay();

        $appointments = Appointment::whereBetween('start_at', [now()->format('Y-m-d'), $gridEnd])
            ->get()
            ->groupBy(fn ($appointment) => $appointment->start_at->format('Y-m-d'));

        $unavailabilities = Unavailability::where('start_at', '<=', $gridEnd)
            ->where('end_at', '>=', $gridStart)
            ->get();

        $recurringRules = RecurringUnavailability::all();

        $slots = generateSlots($currentDate, $totalDuration, $appointments->get($currentDate->format('Y-m-d'), collect()), $unavailabilities, $recurringRules);

        $availableDays = collect($days)->mapWithKeys(function ($day) use ($totalDuration, $appointments, $unavailabilities, $recurringRules) {
            $slots = generateSlots($day, $totalDuration, $appointments->get($day->format('Y-m-d'), collect()), $unavailabilities, $recurringRules);

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
        if (
            ! session('appointment.services') ||
            ! session('appointment.date') ||
            ! session('appointment.slot')
        ) {
            return redirect(route('appointment2'));
        }

        $start = Carbon::parse(
            session('appointment.date').' '.session('appointment.slot')
        );

        $services = Service::find(session('appointment.services'));
        $totalDuration = $services->sum('duration');

        $end = $start->copy()->addMinutes($totalDuration);

        $conflict = Appointment::where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if (session('appointment.edit')) {
            $conflict->where('id', '!=', session('appointment.id'));
        }

        if ($conflict->exists()) {
            return redirect(route('appointment2'))
                ->with('error', 'Ce créneau n\'est malheureusement plus disponible');
        }

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'telephone' => 'required|regex:/^\+?[0-9\s\-\(\)]{7,20}$/',
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

        if (session('appointment.edit')) {
            $appointment = Appointment::findOrFail(session('appointment.id'));

            $appointment->update([
                'client_id' => $client->id,
                'message' => $validated['message'],
                'start_at' => $start,
                'end_at' => $end,
            ]);

            $appointment->services()->sync($services->pluck('id'));

        } else {
            $appointment = Appointment::create([
                'uuid' => Str::uuid(),
                'client_id' => $client->id,
                'message' => $validated['message'],
                'start_at' => $start,
                'end_at' => $end,
            ]);

            $appointment->services()->attach($services->pluck('id'));
        }

        $users = [
            config('mail.reply_to.address'),
            'joanacoiffure190@gmail.com',
        ];

        $mail = session('appointment.edit')
            ? new EditAppointment($appointment)
            : new NewAppointment($appointment);

        foreach ($users as $user) {
            Mail::to($user)->send($mail);
        }

        $client_mail = session('appointment.edit')
            ? new EditAppointmentRecap($appointment)
            : new NewAppointmentRecap($appointment);

        Mail::to($appointment->client->email)->send($client_mail);

        session(['confirmed_appointment_id' => $appointment->id]);
        session()->forget('appointment');

        return redirect(route('thanks', $appointment));
    }

    public function appointment_cancel_view(string $uuid)
    {
        $appointment = Appointment::where('uuid', $uuid)->firstOrFail();

        return view('pages.client.appointment.cancel', compact('appointment'));
    }

    public function appointment_cancel(Appointment $appointment)
    {
        AppointmentService::where('appointment_id', $appointment->id)->delete();
        $appointment->delete();

        return redirect(route('home'))->with('success', 'Votre rendez-vous à bien été annulé !');
    }

    public function appointment_edit(Appointment $appointment)
    {
        $date = $appointment->start_at->format('Y-m-d');
        $slot = $appointment->start_at->format('H:i');

        session([
            'appointment.id' => $appointment->id,
            'appointment.services' => $appointment->services->pluck('id')->toArray(),
            'appointment.date' => $date,
            'appointment.slot' => $slot,
            'appointment.client_name' => $appointment->client->name,
            'appointment.client_email' => $appointment->client->email,
            'appointment.client_telephone' => $appointment->client->telephone,
            'appointment.message' => $appointment->message,
            'appointment.edit' => true,
        ]);

        return redirect(route('appointment'));
    }
}
