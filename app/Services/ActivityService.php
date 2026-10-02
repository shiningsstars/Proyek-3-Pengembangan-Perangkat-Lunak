<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;
use Illuminate\Support\Arr;

class ActivityService
{

    public function create(array $data): Activity
    {
        $data = Arr::except($data, ['status']);
        $data['status'] = Activity::STATUS_DRAFT;

        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update(Arr::except($data, ['status']));

        return $activity->refresh();
    }

    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== Activity::STATUS_DRAFT) {
            throw new DomainException(
                "Activity berstatus {$activity->status} tidak dapat dipublish."
            );
        }

        // BR-05: data wajib lengkap sebelum dipublish.
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

        $activity->update(['status' => Activity::STATUS_PUBLISHED]);

        return $activity->refresh();
    }

    public function complete(Activity $activity): Activity
    {
        if ($activity->status !== Activity::STATUS_PUBLISHED) {
            throw new DomainException(
                "Activity berstatus {$activity->status} tidak dapat diselesaikan."
            );
        }

        $activity->update(['status' => Activity::STATUS_COMPLETED]);

        return $activity->refresh();
    }

    public function restore(Activity $activity): void
    {
        $activity->restore();
    }
}