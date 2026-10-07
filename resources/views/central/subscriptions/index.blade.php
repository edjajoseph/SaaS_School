@extends('layouts.central.app_back', [
    'namePage' => 'Souscription',
    'activePage' => 'central',
    'activePageSb' => 'souscription',
    'activePageSb1' => '',
    
])

@section('content')

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Abonnement</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Souscriptions</a></li>
                            <li class="breadcrumb-item"><a href="#!">Souscriptions Client</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
            <script class="alert alert-success" role="alert">
                @if($message = session('success'))
                    Swal.fire(
                    'Félicitation!',
                    '{{ $message }}',
                    'success'
                    )
                @elseif($message = session('warning'))
                    Swal.fire(
                    'Attention!',
                    '{{ $message }}',
                    'warning'
                    )
                @elseif($message = session('error'))
                    Swal.fire(
                    'Désolé!',
                    '{{ $message }}',
                    'error'
                    )
                @endif
            </script>

                {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Message d'erreur technique/exception --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation des champs (ex: doublon de nom) --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- HTML (DOM) Sourced Data table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Liste des souscriptions</h5>
                    </div>
                    <div class="text-center">
                                <a href="#" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{ route('subscriptions.create') }}"><i class="fa fa-plus"></i>&nbsp;{{__(' Nouvelle Souscription')}}</a>
                            </div>
                            <hr>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="dom-table" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Solution & Plan</th>
                                        <th>Période</th>
                                        <th>Progression</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $sub)
                                        @php
                                            // Calcul du pourcentage de progression du temps écoulé
                                            $progressPercent = 0;
                                            $daysRemaining = 0;
                                            
                                            if ($sub->current_period_starts_at && $sub->current_period_ends_at) {
                                                $totalDays = max(1, $sub->current_period_starts_at->diffInDays($sub->current_period_ends_at));
                                                
                                                if (now()->greaterThanOrEqualTo($sub->current_period_ends_at)) {
                                                    $progressPercent = 100;
                                                    $daysRemaining = 0;
                                                } elseif (now()->lessThan($sub->current_period_starts_at)) {
                                                    $progressPercent = 0;
                                                    $daysRemaining = round(now()->diffInDays($sub->current_period_ends_at));
                                                } else {
                                                    $elapsedDays = $sub->current_period_starts_at->diffInDays(now());
                                                    $progressPercent = min(100, round(($elapsedDays / $totalDays) * 100));
                                                    $daysRemaining = round(now()->diffInDays($sub->current_period_ends_at));
                                                }
                                            }

                                            // Détermination de la couleur selon l'avancement
                                            if ($progressPercent >= 90) {
                                                $barColor = 'bg-danger';
                                            } elseif ($progressPercent >= 75) {
                                                $barColor = 'bg-warning';
                                            } else {
                                                $barColor = 'bg-success';
                                            }
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $sub->tenant->name ?? $sub->tenant_id }}</strong></td>
                                            <td>
                                                <span class="badge badge-light-primary">{{ $sub->solution->name ?? 'Solution' }}</span> - 
                                                <strong>{{ $sub->plan->name ?? 'Plan' }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted d-block">Du {{ $sub->current_period_starts_at ? $sub->current_period_starts_at->format('d/m/Y') : '-' }}</small>
                                                <small class="text-muted d-block">Au {{ $sub->current_period_ends_at ? $sub->current_period_ends_at->format('d/m/Y') : '-' }}</small>
                                            </td>
                                            <td>
                                                @if($sub->isActive())
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 mr-2" style="height: 8px;" title="{{ $daysRemaining }} jour(s) restant(s)">
                                                            <div class="progress-bar {{ $barColor }}" 
                                                                role="progressbar" 
                                                                style="width: {{ $progressPercent }}%;" 
                                                                aria-valuenow="{{ $progressPercent }}" 
                                                                aria-valuemin="0" 
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small class="font-weight-bold text-muted" style="min-width: 35px;">{{ $progressPercent }}%</small>
                                                    </div>
                                                    <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                                        <i class="feather icon-clock mr-1"></i>
                                                        @if($daysRemaining > 0)
                                                            {{ $daysRemaining }} jour(s) restant(s)
                                                        @else
                                                            Expire aujourd'hui
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted font-italic" style="font-size: 12px;">Période inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sub->starts_at && $sub->starts_at->isFuture())
                                                    <span class="badge badge-warning">PLANIFIÉ</span>
                                                @elseif($sub->isActive())
                                                    <span class="badge badge-success">ACTIF</span>
                                                @elseif($sub->status === 'trialing')
                                                    <span class="badge badge-info">ESSAI</span>
                                                @elseif($sub->status === 'canceled')
                                                    <span class="badge badge-danger">ANNULÉ</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ strtoupper($sub->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="#" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2"  data-attr="{{ route('subscriptions.show', $sub) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                                    <i class="feather icon-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Aucune souscription enregistrée.</td>
                                        </tr>
                                    @endforelse                              
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- HTML (DOM) Sourced Data table end -->
            
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>
        

        <div id="mediumModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-primary">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div id="mediumModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody1">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="mediumModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-warning">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody2">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="mediumModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody3">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>
@endsection
