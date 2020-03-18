@extends('layouts.app')

@section('title', $title)
@section('content')
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <h2 class="mb-4 text-center">{{ $title }}</h2>

            <div class="list-group list-group-flush follow-list">
                @foreach ($users as $user)
                    <div class="list-group-item">
                        <img class="mr-3" src="{{ $user->avatar }}" alt="{{ $user->name }}" width=32>
                        <a href="{{ route('users.show', $user) }}">
                            {{ $user->name }}
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {!! $users->render() !!}
            </div>
        </div>
    </div>
@stop
