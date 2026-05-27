<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use App\Models\Unavailabilities;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function generateSlots(Carbon $date, int $duration, Collection $appointments, Collection $unavailabilities, Collection $recurringRules): array
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

    $recurringBlocks = collect();

    foreach ($recurringRules as $rule) {
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
