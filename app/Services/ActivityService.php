<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);

        return $activity->refresh();
    }

    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== 'Draft') {
            throw new DomainException(
                "Activity berstatus {$activity->status} tidak dapat dipublish."
            );
        }

        $missing = collect([
            'start_at' => $activity->start_at,
            'end_at' => $activity->end_at,
            'capacity' => $activity->capacity,
        ])->filter(fn ($value) => $value === null)->keys();

        if ($missing->isNotEmpty()) {
            throw new DomainException(
                'Activity belum lengkap untuk dipublish: '.$missing->implode(', ').'.'
            );
        }

        $activity->update(['status' => 'Published']);

        return $activity->refresh();
    }

    public function complete(Activity $activity): Activity
    {
        if ($activity->status !== 'Published') {
            throw new DomainException(
                "Activity berstatus {$activity->status} tidak dapat diselesaikan."
            );
        }

        $activity->update(['status' => 'Completed']);

        return $activity->refresh();
    }
}