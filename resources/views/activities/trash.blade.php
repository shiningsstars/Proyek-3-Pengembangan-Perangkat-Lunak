@extends('layouts.app')

@section('content')
    <h1>Data Terhapus</h1>

    <a href="{{ route('activities.index') }}">Kembali ke daftar</a>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @forelse ($activities as $activity)
        <article>
            <h2>{{ $activity->code }} - {{ $activity->title }}</h2>
            <p>Kategori: {{ $activity->category?->name ?? '-' }}</p>
            <p>Dihapus pada: {{ $activity->deleted_at->format('d-m-Y H:i') }}</p>

            <form method="POST" action="{{ route('activities.restore', $activity->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit">Restore</button>
            </form>
        </article>
    @empty
        <p>Tidak ada data terhapus.</p>
    @endforelse

    {{ $activities->links() }}
@endsection