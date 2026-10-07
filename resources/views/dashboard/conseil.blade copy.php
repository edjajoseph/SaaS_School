@extends('layouts.app_back')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tableau de Bord du Conseil - EMUCI</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Vue d'ensemble</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Bilan financier global -->
            <div class="col-md-4">
                <div class="card bg-c-green text-white">
                    <div class="card-body">
                        <h6>Entrées (CFA)</h6>
                        <h3>{{ number_format($totalEntreeCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-up f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-red text-white">
                    <div class="card-body">
                        <h6>Sorties (CFA)</h6>
                        <h3>{{ number_format($totalSortieCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-down f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-yellow text-white">
                    <div class="card-body">
                        <h6>Total en Caisse (Net)</h6>
                        <h3>{{ number_format($soldeCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-wallet f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>
            <!-- Fimeco -->
            <div class="col-md-4">
                <div class="card bg-c-green text-white">
                    <div class="card-body">
                        <h6>Famille (FIMECO)</h6>
                        <h3>{{ number_format($totalFamille, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-up f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-red text-white">
                    <div class="card-body">
                        <h6>Souscription (FIMECO)</h6>
                        <h3>{{ number_format($totalSouscriptions, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-hand-holding-heart text-white f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-yellow text-white">
                    <div class="card-body">
                        <h6>Versement (FIMECO)</h6>
                        <h3>{{ number_format($totalVersFimecos, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-leaf text-white f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>
            <!-- Moisson -->
            <div class="col-md-4">
                <div class="card bg-c-green text-white">
                    <div class="card-body">
                        <h6>Cible (MOISSON)</h6>
                        <h3>{{ number_format($cibleMoisson->Cible, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-up f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-red text-white">
                    <div class="card-body">
                        <h6>Recette (MOISSON)</h6>
                        <h3>{{ number_format($totalVersMoissons, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-hand-holding-heart text-white f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-c-yellow text-white">
                    <div class="card-body">
                        <h6>Dépenses (MOISSON)</h6>
                        <h3>{{ number_format($totalDepMoissons, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-leaf text-white f-20 float-right" style="opacity:0.5; margin-top:-30px;"></i>
                    </div>
                </div>
            </div>
            <!-- Ministère -->
            <div class="col-xl-2 col-md-6">
                <div class="card prod-p-card bg-c-red">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Fidèles (Total)</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($totalFideles ?? 0, 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users text-white f-20 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="card prod-p-card bg-c-blue">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Membres Actifs</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($totalMembreActif ?? 0, 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-heart text-white f-20 float-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6">
                <div class="card prod-p-card bg-c-green">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Hommes</h6>
                                <h3 class="m-b-0 text-white">{{ number_format($totalMoissons ?? 0, 0, ',', ' ') }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-leaf text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6">
                <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Femmes</h6>
                                <h3 class="m-b-0 text-white">{{ $comptesActifs ?? 0 }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6">
                <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Jeunes</h6>
                                <h3 class="m-b-0 text-white">{{ $comptesActifs ?? 0 }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6">
                <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Enfants</h6>
                                <h3 class="m-b-0 text-white">{{ $comptesActifs ?? 0 }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-xl-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Collectes Mensuelles (Cumul Entrées)</h5>
                    </div>
                    <div class="card-body">
                        <div id="collecte-graph-mensuel" style="height:300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Répartition des Fidèles</h5>
                    </div>
                    <div class="card-body">
                        <div id="repartition-fidele-donut" style="height:300px;"></div>
                        <div class="row text-center m-t-10">
                            <div class="col-6">
                                <h6 class="text-muted"><i class="fas fa-circle text-c-blue f-10 m-r-5"></i>Hommes</h6>
                                <h6>{{ $pctHommes ?? 0 }}%</h6>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted"><i class="fas fa-circle text-c-red f-10 m-r-5"></i>Femmes</h6>
                                <h6>{{ $pctFemmes ?? 0 }}%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-md-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h5>Journal des Activités Récentes</h5>
                        <div class="card-header-right">
                            <a href="#!" class="btn btn-sm btn-primary">Voir tout le rapport</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Entité / Structure</th>
                                        <th>Type d'apport</th>
                                        <th>Montant</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activites as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->Date_OPERATION)->format('d/m/Y') }}</td>
                                        <td>{{ $item->nom_entite }}</td>
                                        <td>{{ $item->type_libelle }}</td>
                                        <td><strong>{{ number_format($item->Montant_Operation, 0, ',', ' ') }}</strong></td>
                                        <td>
                                            <a href="#!"><i class="icon feather icon-eye f-16 text-c-blue"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted p-20">Aucun mouvement enregistré récemment.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Graphique de Répartition des Fidèles (Donut)
        var donutOptions = {
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ["#4680ff", "#fe5260"], // Bleu pour Hommes, Rouge pour Femmes
            labels: ['Hommes', 'Femmes'],
            series: [{{ $pctHommes }}, {{ $pctFemmes }}],
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            }
        };
        var donutChart = new ApexCharts(document.querySelector("#repartition-fidele-donut"), donutOptions);
        donutChart.render();

        // 2. Graphique des Collectes Mensuelles (Exemple avec données statiques ou à dynamiser)
        var lineOptions = {
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false }
            },
            colors: ["#2ed8b6"],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            series: [{
                name: 'Versements',
                data: [31, 40, 28, 51, 42, 109, 100] // Remplacez par une variable JS passée par le contrôleur
            }],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
            }
        };
        var lineChart = new ApexCharts(document.querySelector("#collecte-graph-mensuel"), lineOptions);
        lineChart.render();
    });

    
</script>
@endsection