@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">Tambah Kegiatan</a>

    <form method="GET" action="{{ route('activities.index') }}">
        <label for="status">Filter Status</label>
        <select name="status" id="status">
            <option value="">Semua</option>
            @foreach ($statuses as $option)
                <option value="{{ $option }}" @selected($status === $option)>
                    {{ $option }}
                </option>
            @endforeach
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
            <p>Tanggal: {{ $activity->activity_date->format('d-m-Y') }}</p>
            <p>Kategori: {{ $activity->category->name }}</p>
            <p>Status: {{ $activity->status }}</p>
        </article>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse
@endsection