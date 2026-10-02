@extends('layouts.app')

@section('content')
    <h1>Detail Kegiatan</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @error('status')
        <p class="error">{{ $message }}</p>
    @enderror

    <h2>{{ $activity->code }} - {{ $activity->title }}</h2>

    <p>{{ $activity->description }}</p>
    <p>
        Jadwal:
        {{ $activity->start_at?->format('d-m-Y H:i') ?? '-' }}
        sampai
        {{ $activity->end_at?->format('d-m-Y H:i') ?? '-' }}
    </p>
    <p>Kapasitas: {{ $activity->capacity ?? '-' }}</p>
    <p>Kategori: {{ $activity->category?->name ?? '-' }}</p>
    <p>Status: {{ $activity->status }}</p>

    <a href="{{ route('activities.edit', $activity) }}">Edit</a>
    <a href="{{ route('activities.index') }}">Kembali ke daftar</a>

    @if ($activity->status === \App\Models\Activity::STATUS_DRAFT)
        <form method="POST" action="{{ route('activities.publish', $activity) }}">
            @csrf
            <button type="submit">Publish</button>
        </form>
    @endif

    @if ($activity->status === \App\Models\Activity::STATUS_PUBLISHED)
        <form method="POST" action="{{ route('activities.complete', $activity) }}">
            @csrf
            <button type="submit">Complete</button>
        </form>
    @endif

    <form method="POST" action="{{ route('activities.destroy', $activity) }}">
        @csrf
        @method('DELETE')

        <button type="submit"
            onclick="return confirm('Yakin ingin menghapus activity ini?')">
            Hapus
        </button>
    </form>
@endsection