@extends('layouts.app')

@section('content')
    <h1>Daftar Kegiatan</h1>

    <a href="{{ route('activities.create') }}">Tambah Kegiatan</a>

    <form method="GET" action="{{ route('activities.index') }}">
        <input
            type="text"
            name="search"
            placeholder="Cari code atau title"
            value="{{ $filters['search'] ?? '' }}"
        >

        <select name="category_id">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected(($filters['category_id'] ?? null) == $category->id)
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="status">
            <option value="">Semua Status</option>
            @foreach (['Draft', 'Published', 'Completed'] as $option)
                <option value="{{ $option }}" @selected(($filters['status'] ?? null) === $option)>
                    {{ $option }}
                </option>
            @endforeach
        </select>

        <select name="sort">
            <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>
                Tanggal Terbaru
            </option>
            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>
                Tanggal Terlama
            </option>
        </select>

        <button type="submit">Terapkan</button>
    </form>

    @forelse ($activities as $activity)
        <article>
            <h2>
                <a href="{{ route('activities.show', $activity) }}">
                    {{ $activity->code }} - {{ $activity->title }}
                </a>
            </h2>
            <p>Kategori: {{ $activity->category->name }}</p>
            <p>Status: {{ $activity->status }}</p>
            <p>
                Jadwal:
                {{ $activity->start_at?->format('d-m-Y H:i') ?? '-' }}
                sampai
                {{ $activity->end_at?->format('d-m-Y H:i') ?? '-' }}
            </p>
            <p>Kapasitas: {{ $activity->capacity ?? '-' }}</p>
        </article>
    @empty
        <p>Belum ada kegiatan.</p>
    @endforelse

    {{ $activities->links() }}
@endsection