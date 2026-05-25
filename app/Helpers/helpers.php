<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailabilities;
use Carbon\Carbon;

function generateSlots(Carbon $date, int $duration): array
{
    $slots = [];

    $start = $date->copy()->setTime(9, 0);
    $end = $date->copy()->setTime(18, 0);

    $now = now();

    if ($date->isToday()) {
        $start = $start->max(
            $now->copy()->addMinutes(15 - ($now->minute % 15))
        );
    }

    if ($start->gte($end)) {
        return [];
    }

    $appointments = Appointment::whereDate('start_at', $date)->get();

    $unavailabilities = Unavailabilities::where('start_at', '<=', $date->copy()->setTime(18, 0))
        ->where('end_at', '>=', $date->copy()->setTime(9, 0))
        ->get();

    $rules = RecurringUnavailability::where('is_active', true)->get();

    $recurringBlocks = collect();

    foreach ($rules as $rule) {
        if (! in_array($date->dayOfWeek, $rule->days_of_week)) {
            continue;
        }

        $recurringBlocks->push([
            'start' => $date->copy()->setTimeFromTimeString($rule->start_time),
            'end' => $date->copy()->setTimeFromTimeString($rule->end_time),
        ]);
    }

    while ($start->copy()->addMinutes($duration) <= $end) {

        $slotEnd = $start->copy()->addMinutes($duration);

        $buffer = 15;

        $overlapAppointment = $appointments->contains(function ($appointment) use ($start, $slotEnd, $buffer) {

            $safeSlotEnd = $slotEnd->copy()->addMinutes($buffer);

            $appointmentEndWithBuffer = $appointment->end_at->copy()->addMinutes($buffer);

            return $start < $appointmentEndWithBuffer &&
                $safeSlotEnd > $appointment->start_at;
        });

        $overlapUnavailability = $unavailabilities->contains(function ($unavailability) use ($start, $slotEnd) {
            return $start < $unavailability->end_at && $slotEnd > $unavailability->start_at;
        });

        $overlapRecurring = $recurringBlocks->contains(function ($block) use ($start, $slotEnd) {
            return $start < $block['end'] && $slotEnd > $block['start'];
        });

        if (! $overlapAppointment && ! $overlapUnavailability && ! $overlapRecurring) {
            $slots[] = [
                'start' => $start->format('H:i'),
                'end' => $slotEnd->format('H:i'),
            ];
        }

        $start->addMinutes(15);
    }

    return $slots;
}
