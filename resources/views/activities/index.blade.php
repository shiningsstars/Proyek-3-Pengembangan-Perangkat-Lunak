@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">
        Tambah Activity
    </a>

    <form method="GET" action="{{ route('activities.index') }}">
        <label for="status">Filter Status</label>

        <select name="status" id="status">
            <option value="">Semua</option>

            <option value="Planned" @selected($status === 'Planned')>
                Planned
            </option>

            <option value="Ongoing" @selected($status === 'Ongoing')>
                Ongoing
            </option>

            <option value="Done" @selected($status === 'Done')>
                Done
            </option>
        </select>

        <button type="submit">Filter</button>
    </form>

    @forelse ($activities as $activity)
        <article>
            <h2>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->title }}
                </a>
            </h2>

            <p>{{ $activity->description }}</p>

            <p>
                Tanggal:
                {{ $activity->activity_date->format('d-m-Y') }}
            </p>

            <p>Kategori: {{ $activity->category }}</p>

            <p>Status: {{ $activity->status }}</p>
        </article>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse
@endsection