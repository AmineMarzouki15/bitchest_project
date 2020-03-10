@extends('layouts.dashboard')

@section('title', 'liste utilisateurs')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mon Portefeuille</h1>
    </div>

    <div class="row" style="margin-bottom:20px;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('home') }}">Tableau de bord</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Mettre à jour mon solde</button>
            </div>
        </div>
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
            <th>Crypto-monnaie</th>
            <th>Cour actuel</th>
        </tr>
        @foreach ($arrPayments as $crypto)
            <tr>
                <td>
                    <a href="{{ route('payment.detail', $crypto->id) }}" style="text-decoration: none"> <img src="{{ asset('img/' . $crypto->icon_name . '.png') }}" alt=""> {{ $crypto->name }} </a>
                </td>
                <td>{{ $crypto->current_cour }} € </td>
            </tr>
        @endforeach
    </table>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mettre à jour mon solde</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('profile.balance') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Montant') }} €</label>
                            <div class="col-md-6">
                                <input id="amount" type="number" class="form-control" name="amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
