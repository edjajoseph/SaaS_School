@extends('layouts.app_back')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tableau de bord - Moisson {{ $anneeEnCours->Lib_Annee }}</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Statistiques Annuelles</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl col-md-4 col-sm-6">
                <div class="card prod-p-card bg-c-red">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Moisson</h6>
                                <h3 class="m-b-0 text-white">{{ $anneeEnCours->Lib_Annee }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-4 col-sm-6">
                <div class="card prod-p-card bg-c-blue">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Cible</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($stats['total_cible'], 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullseye text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-4 col-sm-6">
                <div class="card prod-p-card bg-c-green">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Recettes</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($stats['total_recettes'], 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-usd text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6 col-sm-6">
                <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Dépenses</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($stats['total_charges'], 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl col-md-6 col-sm-12">
                <div class="card prod-p-card {{ $stats['marge_totale'] >= 0 ? 'bg-c-purple' : 'bg-danger' }}" 
                     style="{{ $stats['marge_totale'] >= 0 ? 'background: linear-gradient(45deg, #7267CB, #9E7CF1);' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Marge (Solde)</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($stats['marge_totale'], 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Évolution Mensuelle des Recettes</h5>
                    </div>
                    <div class="card-body">
                        <div id="mon-graphique-recettes"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="card feed-card">
                    <div class="card-header">
                        <h5>Performance par Moisson</h5>
                    </div>
                    <div class="feed-scroll" style="height:345px;position:relative;">
                        <div class="card-body">
                            @foreach($topMoissons as $tm)
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="fas fa-chart-line bg-c-blue feed-icon"></i>
                                </div>
                                <div class="col">
                                    <h6 class="m-b-5">{{ $tm->Lib_moisson }}</h6>
                                    <p class="text-muted m-b-0">Cumul : <strong>{{ number_format($tm->total, 0, ',', ' ') }} F</strong></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h5>Derniers Versements Enregistrés</h5>
                    </div>
                    <div class="pro-scroll" style="height:345px;position:relative;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Libellé / Objet</th>
                                            <th>Moisson</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($derniersVersements as $v)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v->Date_Operation)) }}</td>
                                            <td>{{ $v->Lib_Objet }}</td>
                                            <td>{{ $v->Lib_moisson }}</td>
                                            <td><span class="font-weight-bold text-c-green">{{ number_format($v->Montant_Operation, 0, ',', ' ') }} F</span></td>
                                            <td><label class="badge badge-light-success">Validé</label></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        @php
            $pointsDeDonnees = [];
            $dataCollection = collect($evolutionRecettes);
            for ($i = 1; $i <= 12; $i++) {
                $moisTrouve = $dataCollection->firstWhere('mois', $i);
                $pointsDeDonnees[] = $moisTrouve ? (float)$moisTrouve->total : 0;
            }
        @endphp

        var options = {
            chart: { 
                type: 'area', // Type 'area' pour avoir le remplissage sous la courbe
                height: 350,
                toolbar: { show: true },
                zoom: { enabled: false }
            },
            colors: ["#1abc9c"], // Votre couleur turquoise
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            series: [{
                name: 'Recettes',
                data: {!! json_encode($pointsDeDonnees) !!}
            }],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                axisBorder: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        // Formate les grands nombres (ex: 1 000 000 F)
                        return val.toLocaleString() + " F";
                    }
                }
            },
            tooltip: {
                theme: 'light',
                x: { show: true },
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " FCFA";
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var chart = new ApexCharts(document.querySelector("#mon-graphique-recettes"), options);
        chart.render();
    });
</script>
@endsection