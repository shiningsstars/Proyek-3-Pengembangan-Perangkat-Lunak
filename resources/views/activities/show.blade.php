@extends('layouts.app')

@section('content')
    <h1>Detail Kegiatan</h1>

    <h2>{{ $activity->title }}</h2>

    <p>{{ $activity->description }}</p>
    <p>Tanggal: {{ $activity->activity_date->format('d-m-Y') }}</p>
    <p>Kategori: {{ $activity->category }}</p>
    <p>Status: {{ $activity->status }}</p>

    <a href="{{ route('activities.edit', $activity) }}">Edit</a>

    <a href="{{ route('activities.index') }}">Kembali ke daftar</a>

    <form method="POST" action="{{ route('activities.destroy', $activity) }}">
        @csrf
        @method('DELETE')

        <button type="submit"
            onclick="return confirm('Yakin ingin menghapus activity ini?')">
            Hapus
        </button>
    </form>
@endsection