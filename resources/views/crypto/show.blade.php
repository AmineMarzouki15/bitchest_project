@extends('layouts.dashboard')

@section('title', 'ajouter utilisateur')

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $crypto->name }}</h1>
    </div>

    <div class="row" style="margin-bottom:20px;">
        <div class="col-lg-12 margin-tb">
            <h2 class="h2">Valeur du cour : {{ $crypto->current_cour }} €</h2>
        </div>
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-info" href="{{ route('crypto.index') }}">Liste Crypto-monnaies</a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"> Acheter</button>
            </div>
        </div>

        <div class="col-md-12" style="margin-top: 10px">
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
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="h4">Evolution graphique du cour des 30 derniers jours</h3>
            <div id="chart_div"></div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Achat {{ $crypto->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('payment.save') }}">
                    <input type="hidden" value="{{ $crypto->id }}" name="crypto_id">
                    @csrf
                    <div class="modal-body">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Quantité') }}</label>
                                <div class="col-md-6">
                                    <input id="quantity" type="number" class="form-control" name="quantity" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Montant Achat') }}
                                </label>
                                <div class="col-md-6 col-form-label">
                                    <span id="buy_value">0 €</span>
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

    <script type="application/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawBackgroundColor);

        function drawBackgroundColor() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Cour');
            var arrData = []
            @foreach($crypto->cours as $cour)
                arrData.push([new Date('{{ $cour->created_at }}'), {{ $cour->value }}])
            @endforeach
            data.addRows(arrData);
            var options = {
                hAxis: {
                    title: 'Date'
                },
                vAxis: {
                    title: 'Montant'
                },
                backgroundColor: '#f1f8e9'
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        $('#quantity').keyup(function() {
            var quantity    =   $('#quantity').val() == '' ? 0 : $('#quantity').val();
            var amount      =   parseFloat('{{ $crypto->current_cour }}') * quantity;
            $('#buy_value').html(amount.toFixed(2) + ' €');
        })

    </script>

@endsection
