<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService;
use DomainException;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    public function index()
    {
        $status = request('status');

        $validStatuses = ['Planned', 'Ongoing', 'Done'];

        $activities = Activity::query()
            ->when(
                in_array($status, $validStatuses, true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderBy('activity_date')
            ->get();

        return view('activities.index', compact('activities', 'status'));
    }

    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(
        StoreActivityRequest $request,
        ActivityService $service
    ): RedirectResponse {
        $activity = $service->create($request->validated());

        return redirect()
            ->route('activities.show', $activity)
            ->with('success', 'Activity berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(
        UpdateActivityRequest $request,
        Activity $activity,
        ActivityService $service
    ): RedirectResponse {
        try {
            $service->update($activity, $request->validated());
        } catch (DomainException $exception) {
            return back()
                ->withErrors(['status' => $exception->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('activities.show', $activity)
            ->with('success', 'Activity berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity berhasil dihapus.');
    }
}