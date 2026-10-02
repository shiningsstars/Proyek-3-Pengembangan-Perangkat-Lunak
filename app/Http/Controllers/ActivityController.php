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
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(fn ($q) => $q
                    ->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%"));
            })
            ->when($request->filled('category_id'), fn ($query) => $query
                ->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query
                ->where('status', $request->string('status')))
            ->orderBy('start_at', $request->input('sort') === 'oldest' ? 'asc' : 'desc')
            ->paginate(2)
            ->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'categories' => Category::orderBy('name')->get(),
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
            ->with('success', 'Kegiatan berhasil ditambahkan.');
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
            'statuses'   => ['Draft', 'Published', 'Completed'],
        ];
    }
}