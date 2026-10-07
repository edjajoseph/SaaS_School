@extends('layouts.app_back')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tableau de Bord du Régisseur FIMECO</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Statistiques Financières</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-blue shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white f-w-600">Total Souscription</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($total_souscription ?? 0, 0, ',', ' ') }} <small>CFA</small></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-signature text-white f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-green shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white f-w-600">Versements Familles</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($total_versement_famille ?? 0, 0, ',', ' ') }} <small>CFA</small></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-usd text-white f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-yellow shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white f-w-600">Versements District</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($total_versement_district ?? 0, 0, ',', ' ') }} <small>CFA</small></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-church text-white f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-red shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white f-w-600">Reste à Payer</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($reste_a_payer ?? 0, 0, ',', ' ') }} <small>CFA</small></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-circle text-white f-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Évolution Mensuelle des Recouvrements</h5>
                    </div>
                    <div class="card-body">
                        <div id="fimeco-recouvrement-chart" style="height:300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="card feed-card shadow-sm">
                    <div class="card-header">
                        <h5>Encaissements Récents</h5>
                    </div>
                    <div class="feed-scroll" style="height:330px;position:relative;">
                        <div class="card-body">
                            @forelse($recent_payments ?? [] as $pay)
                            <div class="row m-b-20 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-arrow-down-left badge-light-success feed-icon"></i>
                                </div>
                                <div class="col">
                                    <h6 class="m-b-0">
                                        {{ $pay->Nom_Famille }} 
                                        <span class="text-muted float-right f-12">
                                            {{ \Carbon\Carbon::parse($pay->Date_operation)->diffForHumans() }}
                                        </span>
                                    </h6>
                                    <span class="text-success font-weight-bold">+ {{ number_format($pay->Montant_operation, 0, ',', ' ') }} CFA</span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-t-50">
                                <p class="text-muted">Aucun versement enregistré.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-md-12">
                <div class="card table-card shadow-sm">
                    <div class="card-header">
                        <h5>Situation par Famille (Top 10 Progressions)</h5>
                    </div>
                    <div class="pro-scroll" style="height:350px;position:relative;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Nom Famille</th>
                                            <th>Dû</th>
                                            <th>Payé</th>
                                            <th>Taux (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_familles ?? [] as $f)
                                        <tr>
                                            <td>{{ $f->Code_Fimeco }}</td>
                                            <td>{{ $f->Nom_Famille }}</td>
                                            <td>{{ number_format($f->total_du, 0, ',', ' ') }}</td>
                                            <td class="text-primary font-weight-bold">{{ number_format($f->total_paye, 0, ',', ' ') }}</td>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <div class="progress" style="width:100px; height:6px;">
                                                        <div class="progress-bar {{ $f->taux < 50 ? 'bg-danger' : 'bg-success' }}" role="progressbar" style="width: {{ $f->taux }}%;"></div>
                                                    </div>
                                                    <small class="ml-2">{{ round($f->taux) }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Démographie des Chefs de Famille ({{ $total_chefs }})</h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="satisfaction-chart" style="height:250px;"></div>
                        <div class="row m-t-20">
                            <div class="col-6 border-right">
                                <h4 class="m-b-0 font-weight-bold text-primary">{{ $total_hommes }}</h4>
                                <span class="text-muted">Hommes (Chefs)</span>
                            </div>
                            <div class="col-6">
                                <h4 class="m-b-0 font-weight-bold text-danger">{{ $total_femmes }}</h4>
                                <span class="text-muted">Femmes (Chefs)</span>
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
    (function() {
        "use strict";
        
        // On attend que la fenêtre soit totalement prête
        window.addEventListener('load', function() {
            
            // Récupération des données PHP
            var dataFimeco = @json($recouvrements_mensuels);
            
            var options = {
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                series: [{
                    name: 'Montant Encaissé',
                    data: dataFimeco
                }],
                colors: ["#4680ff"], // Le bleu FIMECO
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        type: 'vertical',
                        opacityFrom: 0.4,
                        opacityTo: 0.0,
                        stops: [0, 100]
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                    axisBorder: { show: false },
                },
                tooltip: {
                    y: { formatter: function(val) { return val.toLocaleString() + " CFA"; } }
                }
            };

            // On dessine le graphique dans le NOUVEL ID
            var chart = new ApexCharts(document.querySelector("#fimeco-recouvrement-chart"), options);
            
            // Délai de sécurité de 300ms pour laisser le template finir ses calculs
            setTimeout(function() {
                chart.render();
            }, 300);
        });
    })();
</script>
@endsection