@extends('layouts.app_back')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10 text-uppercase">Tableau de Bord Stratégique - EMUCI</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12"><h6 class="text-muted text-uppercase font-weight-bold m-b-15">I. Trésorerie Comité CFA</h6></div>
            <div class="col-md-4">
                <div class="card bg-c-green text-white">
                    <div class="card-body">
                        <h6>Entrées Globales</h6>
                        <h3>{{ number_format($totalEntreeCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-up float-right opacity-50" style="margin-top:-30px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-c-red text-white">
                    <div class="card-body">
                        <h6>Sorties Globales</h6>
                        <h3>{{ number_format($totalSortieCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-arrow-down float-right opacity-50" style="margin-top:-30px;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-c-blue text-white">
                    <div class="card-body">
                        <h6>Solde en Caisse</h6>
                        <h3>{{ number_format($soldeCFA, 0, ',', ' ') }} <small>FCFA</small></h3>
                        <i class="fas fa-wallet float-right opacity-50" style="margin-top:-30px;"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 m-t-15"><h6 class="text-muted text-uppercase font-weight-bold m-b-15">II. Activités & Programmes</h6></div>
            <div class="col-md-6">
                <div class="card border-left border-info">
                    <div class="card-body">
                        <h6 class="text-info"><i class="fas fa-users m-r-10"></i> FIMECO (Familles)</h6>
                        <div class="row text-center m-t-20">
                            <div class="col-4">
                                <p class="m-b-5 text-muted small">Familles</p>
                                <h5 class="m-b-0">{{ $totalFamille }}</h5>
                            </div>
                            <div class="col-4">
                                <p class="m-b-5 text-muted small">Souscrit</p>
                                <h5 class="m-b-0">{{ number_format($totalSouscriptions, 0, ',', ' ') }}</h5>
                            </div>
                            <div class="col-4">
                                <p class="m-b-5 text-muted small text-success font-weight-bold">Versé</p>
                                <h5 class="m-b-0 text-success">{{ number_format($totalVersFimecos, 0, ',', ' ') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-left border-warning">
                    <div class="card-body">
                        <h6 class="text-warning"><i class="fas fa-leaf m-r-10"></i> MOISSON</h6>
                        <div class="row text-center m-t-20">
                            <div class="col-4">
                                <p class="m-b-5 text-muted small">Objectif</p>
                                <h5 class="m-b-0">{{ number_format($cibleMoisson->Cible ?? 0, 0, ',', ' ') }}</h5>
                            </div>
                            <div class="col-4">
                                <p class="m-b-5 text-muted small text-success">Récolte</p>
                                <h5 class="m-b-0 text-success">{{ number_format($totalVersMoissons, 0, ',', ' ') }}</h5>
                            </div>
                            <div class="col-4">
                                <p class="m-b-5 text-muted small text-danger">Dépenses</p>
                                <h5 class="m-b-0 text-danger">{{ number_format($totalDepMoissons, 0, ',', ' ') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 m-t-15"><h6 class="text-muted text-uppercase font-weight-bold m-b-15">III. État du Ministère (Fidèles)</h6></div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body p-20">
                        <p class="text-muted m-b-10">Total</p>
                        <h3 class="m-b-0">{{ $totalFideles }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card text-center border-bottom border-primary shadow-sm">
                    <div class="card-body p-20">
                        <p class="text-primary m-b-10">Hommes</p>
                        <h4 class="m-b-0 font-weight-bold">{{ $totalHommes }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card text-center border-bottom border-danger shadow-sm">
                    <div class="card-body p-20">
                        <p class="text-danger m-b-10">Femmes</p>
                        <h4 class="m-b-0 font-weight-bold">{{ $totalFemmes }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card text-center border-bottom border-warning shadow-sm">
                    <div class="card-body p-20">
                        <p class="text-warning m-b-10">Jeunes</p>
                        <h4 class="m-b-0 font-weight-bold">{{ $totalJeunes }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card text-center border-bottom border-success shadow-sm">
                    <div class="card-body p-20">
                        <p class="text-success m-b-10">Enfants</p>
                        <h4 class="m-b-0 font-weight-bold">{{ $totalEnfants }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card bg-facebook text-white shadow-sm">
                    <div class="card-body p-20 text-center">
                        <p class="m-b-10">Actifs</p>
                        <h4 class="m-b-0 font-weight-bold">{{ $totalMembreActif }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-md-12 m-t-20">
                <div class="card">
                    <div class="card-header">
                        <h5>Flux Mensuel des Entrées ({{ date('Y') }})</h5>
                    </div>
                    <div class="card-body">
                        <div id="collecte-graph-mensuel" style="height:250px;"></div>
                    </div>
                </div>

                <div class="card m-t-20">
                    <div class="card-header">
                        <h5>Évolution des 10 Derniers Cultes (Fréquentation)</h5>
                    </div>
                    <div class="card-body">
                        <div id="evolution-culte-line" style="height:250px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-12 m-t-20">
                <div class="card shadow-sm" style="height: calc(100% - 20px);">
                    <div class="card-header">
                        <h5>Répartition Moyenne des Cultes</h5>
                    </div>
                    <div class="card-body">
                        <div id="repartition-fidele-donut" style="height:280px;"></div>
                        <div class="text-center m-t-15">
                            <h4 class="m-b-0 font-weight-bold text-primary">{{ $moyenneGeneraleCulte }}</h4>
                            <p class="text-muted small">Moyenne Globale</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 m-t-20">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Moyenne de Présence aux Cultes</h5>
                    </div>
                    <div class="card-body">
                        <div id="repartition-fidele-donut" style="height:250px;"></div>
                        
                        <div class="row text-center m-t-15">
                            <div class="col-12">
                                <h4 class="m-b-0 font-weight-bold text-primary">{{ $moyenneGeneraleCulte }}</h4>
                                <p class="text-muted small">Moyenne de fidèles / Dimanche</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row text-center">
                            <div class="col-4 p-l-0">
                                <p class="m-b-5 text-muted small">Hommes</p>
                                <h6 class="m-b-0">{{ round($statsCultes->avg_h ?? 0) }}</h6>
                            </div>
                            <div class="col-4 border-left">
                                <p class="m-b-5 text-muted small">Femmes</p>
                                <h6 class="m-b-0">{{ round($statsCultes->avg_f ?? 0) }}</h6>
                            </div>
                            <div class="col-4 border-left p-r-0">
                                <p class="m-b-5 text-muted small">Enfants</p>
                                <h6 class="m-b-0">{{ round($statsCultes->avg_e ?? 0) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12">
                <div class="card table-card">
                    <div class="card-header"><h5>Activités Financières Récentes</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <tr>
                                        <th>Date</th><th>Entité</th><th>Libellé</th><th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activites as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->Date_OPERATION)->format('d/m/Y') }}</td>
                                        <td>{{ $item->nom_entite ?? 'ÉGLISE' }}</td>
                                        <td><span class="badge badge-light-primary">{{ $item->type_libelle }}</span></td>
                                        <td><strong>{{ number_format($item->Montant_Operation, 0, ',', ' ') }}</strong></td>
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
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Graphique Financier (Inchangé)
        var optionsFlux = {
            chart: { type: 'area', height: 250, toolbar: { show: false } },
            colors: ["#2ed8b6"],
            series: [{ name: 'Entrées CFA', data: @json($statsMensuelles) }],
            xaxis: { categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'] }
        };
        new ApexCharts(document.querySelector("#collecte-graph-mensuel"), optionsFlux).render();

        // 2. NOUVEAU : Graphique Évolution des 10 derniers cultes
        var optionsEvol = {
            chart: { type: 'line', height: 250, toolbar: { show: false } },
            colors: ["#4680ff"], // Bleu pour la fréquentation
            stroke: { curve: 'smooth', width: 4 },
            series: [{
                name: 'Total Présences',
                data: [ @foreach($evolutionCulte as $e) {{ $e->total }}, @endforeach ]
            }],
            xaxis: {
                categories: [ @foreach($evolutionCulte as $e) "{{ \Carbon\Carbon::parse($e->Date_Culte)->format('d/m') }}", @endforeach ],
            },
            markers: { size: 5 },
            dataLabels: { enabled: true } // Affiche le chiffre exact au dessus de chaque point
        };
        new ApexCharts(document.querySelector("#evolution-culte-line"), optionsEvol).render();

        // 3. Donut : Moyennes (H/F/E)
        var donutOptions = {
            chart: { type: 'donut', height: 280 },
            colors: ["#4680ff", "#fe5260", "#2ed8b6"],
            labels: ['Moy. Hommes', 'Moy. Femmes', 'Moy. Enfants'],
            series: [
                {{ round($statsCultes->avg_h ?? 0) }}, 
                {{ round($statsCultes->avg_f ?? 0) }}, 
                {{ round($statsCultes->avg_e ?? 0) }}
            ],
            dataLabels: {
                enabled: true,
                formatter: function (val) { return Math.round(val) + "%" }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Moyenne',
                                formatter: function (w) { return {{ $moyenneGeneraleCulte }} }
                            }
                        }
                    }
                }
            },
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#repartition-fidele-donut"), donutOptions).render();
    });
</script>
@endsection