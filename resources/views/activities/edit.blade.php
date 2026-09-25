@extends('layouts.app')

@section('content')
    <h1>Edit Activity</h1>

    <form method="POST" action="{{ route('activities.update', $activity) }}">
        @csrf
        @method('PUT')

        @include('activities._form')

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('activities.show', $activity) }}">Batal</a>
@endsection