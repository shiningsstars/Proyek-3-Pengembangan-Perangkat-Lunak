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
    public function index(Request $request): View
    {
        $activities = Activity::query()
            ->with('category')
            ->search($request->query('search'))
            ->ofCategory($request->integer('category_id') ?: null)
            ->ofStatus($request->query('status'))
            ->sortByStart($request->query('sort'))
            ->paginate(10)
            ->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'categories' => Category::orderBy('name')->get(),
            'statuses' => Activity::STATUSES,
            'filters' => $request->only(['search', 'category_id', 'status', 'sort']),
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

    public function store(StoreActivityRequest $request, ActivityService $service): RedirectResponse
    {
        $activity = $service->create($request->validated());

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil ditambahkan sebagai Draft.');
    }

    public function edit(Activity $activity): View
    {
        return view('activities.edit', [
            'activity' => $activity,
            ...$this->formData(),
        ]);
    }

    public function update(UpdateActivityRequest $request, Activity $activity, ActivityService $service): RedirectResponse
    {
        $service->update($activity, $request->validated());

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return to_route('activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function trash(): View
    {
        $activities = Activity::onlyTrashed()
            ->with('category')
            ->latest('deleted_at')
            ->paginate(10);

        return view('activities.trash', compact('activities'));
    }

    public function restore(int $id, ActivityService $service): RedirectResponse
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);
        $service->restore($activity);

        return to_route('activities.trash')
            ->with('success', 'Kegiatan berhasil direstore.');
    }

    public function publish(Activity $activity, ActivityService $service): RedirectResponse
    {
        try {
            $service->publish($activity);
        } catch (DomainException $exception) {
            return back()->withErrors(['status' => $exception->getMessage()]);
        }

        return back()->with('success', 'Activity berhasil dipublish.');
    }

    public function complete(Activity $activity, ActivityService $service): RedirectResponse
    {
        try {
            $service->complete($activity);
        } catch (DomainException $exception) {
            return back()->withErrors(['status' => $exception->getMessage()]);
        }

        return back()->with('success', 'Activity berhasil diselesaikan.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(),
        ];
    }
}