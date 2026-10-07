@extends('School::layouts.app3', [
    'namePage' => 'Gestion des taux horaires par cours',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'payroll.teacher-rates',
    'activeModule' => 'paye',
])

@section('content')
<style>
hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
.progress-xs {
    height: 6px;
    border-radius: 3px;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-percent mr-2"></i>{{ __("RÉMUNÉRATION ET VOLUMES HORAIRES PAR COURS") }}
                    </h4>
                </div>
                <hr class="hr-gradient">                   
                
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    @if($message = session('success'))
                        <script>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '{{ $message }}', confirmButtonColor: '#1ab394' });
                        </script>
                    @elseif($message = session('warning'))
                        <script>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '{{ $message }}', confirmButtonColor: '#f8ac59' });
                        </script>
                    @elseif($message = session('error'))
                        <script>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '{{ $message }}', confirmButtonColor: '#ed5565' });
                        </script>
                    @endif

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('school.accounting.teacher-rates.index') }}" class="mb-4" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-building text-primary mr-1"></i> {{ __("Filtrer par école") }}
                                    </label>
                                    <select name="school_id" class="form-control form-control-alternative" onchange="this.form.submit()">
                                        <option value="">-- Sélectionner une école --</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                                {{ $school->name ?? $school->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-user text-primary mr-1"></i> {{ __("Filtrer par enseignant") }}
                                    </label>
                                    <select name="staff_id" class="form-control form-control-alternative" onchange="this.form.submit()">
                                        <option value="">-- Tous les enseignants --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('staff_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->personne->nom_complet ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-university text-primary mr-1"></i> {{ __("Filtrer par classe") }}
                                    </label>
                                    <select name="class_id" class="form-control form-control-alternative">
                                        <option value="">-- Toutes les classes --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm w-100">
                                    <i class="fa fa-filter mr-1"></i> {{ __("Filtrer") }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table des taux et avancements -->
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Classe</th>
                                    <th>Matière</th>
                                    <th class="text-center">Vol. Prévu / Exécuté</th>
                                    <th class="text-center" style="min-width: 130px;">Progression</th>
                                    <th class="text-right">Taux CM</th>
                                    <th class="text-right">Taux TD</th>
                                    <th class="text-right">Taux TP</th>
                                    <th class="text-right">Taux Exam</th>
                                    <th class="text-center">Support</th>
                                    <th width="8%" class="text-center">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjectRates as $rate)
                                    @php
                                        $planned = (float) ($rate->total_volume ?? ($rate->volume_cm + $rate->volume_td + $rate->volume_tp + $rate->volume_examen));
                                        $executed = (float) ($rate->total_executed_hours ?? 0);
                                        $percentage = $planned > 0 ? min(100, round(($executed / $planned) * 100, 1)) : 0;
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold text-dark">
                                            {{ $rate->staff->personne->nom_complet ?? 'N/A' }}
                                        </td>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $rate->schoolClass->name ?? '-' }}</span></td>
                                        <td class="font-weight-bold">{{ $rate->subject->subject->name ?? '-' }}</td>
                                        
                                        <!-- Volume Horaire Total vs Exécuté -->
                                        <td class="text-center">
                                            <span class="badge badge-dark" title="Volume Prévu">{{ $planned }} h</span>
                                            <i class="fa fa-arrow-right text-muted mx-1" style="font-size: 10px;"></i>
                                            <span class="badge {{ $executed >= $planned && $planned > 0 ? 'badge-success' : 'badge-info' }}" title="Volume Exécuté">
                                                {{ $executed }} h
                                            </span>
                                        </td>

                                        <!-- Barre de progression de cours -->
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <small class="font-weight-bold {{ $percentage >= 100 ? 'text-success' : 'text-primary' }}">
                                                    {{ $percentage }}%
                                                </small>
                                                @if($rate->is_completed || $percentage >= 100)
                                                    <span class="badge badge-success px-1" title="Cours achevé"><i class="fa fa-check"></i> Terminé</span>
                                                @endif
                                            </div>
                                            <div class="progress progress-xs mb-0">
                                                <div class="progress-bar {{ $percentage >= 100 ? 'bg-success' : ($percentage > 50 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-right font-weight-bold">{{ number_format($rate->rate_cm, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right font-weight-bold">{{ number_format($rate->rate_td, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right font-weight-bold">{{ number_format($rate->rate_tp, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right font-weight-bold">{{ number_format($rate->rate_examen, 0, ',', ' ') }} FCFA</td>
                                        
                                        <!-- Statut Support de cours -->
                                        <td class="text-center">
                                            @if($rate->materials && $rate->materials->count() > 0)
                                                <span class="badge badge-success px-2 py-1" title="{{ $rate->materials->count() }} fichier(s) disponible(s)">
                                                    <i class="fa fa-file-text mr-1"></i> {{ $rate->materials->count() }} Doc(s)
                                                </span>
                                            @else
                                                <span class="badge badge-light text-muted border px-2 py-1">
                                                    <i class="fa fa-times mr-1"></i> Aucun
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($rate->is_customized)
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-handshake-o mr-1"></i>Spécifique</span>
                                            @else
                                                <span class="badge badge-info px-2 py-1"><i class="fa fa-globe mr-1"></i>Grille Globale</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('school.accounting.teacher-rates.show', $rate->id) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('school.accounting.teacher-rates.edit', $rate->id) }}" title="Ajuster les taux & volumes">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a class="btn btn-info btn-mini mr-1" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('academic.materials.create', $rate->id) }}" title="Joindre des documents">
                                                    <i class="fa fa-upload"></i>
                                                </a>

                                                @if($rate->is_customized)
                                                    <form action="{{ route('school.accounting.teacher-rates.reset', $rate->id) }}" method="POST" class="d-inline" 
                                                        onsubmit="event.preventDefault(); Swal.fire({
                                                            title: 'Réinitialiser le tarif ?',
                                                            text: 'Ce cours basculera à nouveau sur les montants de la grille globale.',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#f8ac59',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Oui, réinitialiser !',
                                                            cancelButtonText: 'Annuler'
                                                        }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-mini" title="Réinitialiser">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune attribution de cours, volume ou tarif enregistré.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $subjectRates->links() }}
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modals dynamiques -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-warning">
                <h5 class="modal-title text-info"><i class="fa fa-book mr-1"></i> JOINDRE LES SUPPORTS DE COURS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-info-circle mr-1"></i> DÉTAILS D'ATTRIBUTION DU COURS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-success"><i class="fa fa-pencil mr-1"></i> AJUSTEMENT TAUX & VOLUMES HORAIRES</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection