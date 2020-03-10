@extends('layouts.dashboard')

@section('title', 'liste utilisateurs')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Historique achat : {{ $crypto->name }}</h1>
    </div>

    <div class="row" style="margin-bottom:20px;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('portefeuille.show') }}">Mes crypto-monnaies</a>
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
            <th>Date</th>
            <th>Quantité</th>
            <th>Cours</th>
            <th>Etat de mon achat</th>
        </tr>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('d/m/Y h:i:s') }}  </td>
                <td>{{ $payment->quantity }}  </td>
                <td>{{ $payment->cour->value }} € </td>
                <td>
                    @if(!$payment->is_sale)
                        <label class="badge badge-success">Encore disponible</label>
                    @else
                        <label class="badge badge-danger">Déjà vendu</label>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">En cas de vente</h5>
            @if ($benefic_amount >= 0)
                <h6 class="card-subtitle mb-2 text-muted">Gain de : + {{ $benefic_amount }} €</h6>
            @else
                <h6 class="card-subtitle mb-2 text-muted">Perte de : - {{ $benefic_amount }} €</h6>
            @endif
            <p class="card-text">
                En cas de vente de votre stock de {{ $crypto->name }} :
                @if ($benefic_amount >= 0)
                    <span>Vous gagnerez un montant de  {{ $benefic_amount }} € par rapport à votre montant d'achat</span>
                @else
                    <span>Vous perdrez un montant de  {{ $benefic_amount }} € par rapport à votre montant d'achat</span>
                @endif
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('payment.sale', $crypto->id) }}" class="btn btn-danger" style="width: 100%">Vendre mes achats</a>
        </div>
    </div>

@endsection
