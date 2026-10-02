<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Category;
use App\Services\ActivityService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    private const STATUSES = ['Planned', 'Ongoing', 'Done'];

    public function index(Request $request): View
    {
        $status = $request->query('status');

        $activities = Activity::query()
            ->with('category')
            ->when(
                in_array($status, self::STATUSES, true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderBy('activity_date')
            ->get();

        return view('activities.index', [
            'activities' => $activities,
            'status' => $status,
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }

    public function create(): View
    {
        return view('activities.create', $this->formData());
    }

    public function store(
        StoreActivityRequest $request,
        ActivityService $service
    ): RedirectResponse {
        $activity = $service->create($request->validated());

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Activity $activity): View
    {
        return view('activities.edit', [
            'activity' => $activity,
            ...$this->formData(),
        ]);
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

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return to_route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(),
            'statuses' => self::STATUSES,
        ];
    }
}