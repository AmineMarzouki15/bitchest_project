@extends('layouts.dashboard')

@section('title', 'liste cryptos')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Le cour des crypto-monnaies</h1>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Crypto monnaie</th>
            <th>Cour actuel</th>
        </tr>
        @foreach ($cryptos as $crypto)
            <tr>
                <td>
                    <a href="{{ route('crypto.show', $crypto->id) }}" style="text-decoration: none"> <img src="{{ asset('img/' . $crypto->icon_name . '.png') }}" alt=""> {{ $crypto->name }} </a>
                </td>
                <td>{{ $crypto->current_cour }} € </td>
            </tr>
        @endforeach
    </table>

@endsection
