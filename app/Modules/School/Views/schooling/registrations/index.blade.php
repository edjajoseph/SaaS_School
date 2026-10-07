@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Inscriptions',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'registrations',
    'activeModule' => 'scolarite',
])

@section('content')
<style>
hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
@media (min-width: 992px) {
    .modal-xl { max-width: 100% !important; width: 1200px !important; }
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold"><i class="feather icon-file-text mr-2"></i>INSCRIPTIONS & RÉINSCRIPTIONS</h4>
                    <a data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('schooling.registrations.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouvelle Inscription">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle Inscription
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')
                    
                    @if(session('success'))
                        <script>
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '{{ session('success') }}', confirmButtonColor: '#1ab394' });
                        </script>
                    @elseif(session('warning'))
                        <script>
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '{{ session('warning') }}', confirmButtonColor: '#f8ac59' });
                        </script>
                    @elseif(session('error'))
                        <script>
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '{{ session('error') }}', confirmButtonColor: '#ed5565' });
                        </script>
                    @endif

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('schooling.registrations.index') }}" class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">École</label>
                            <select name="school_id" class="form-control form-control-alternative">
                                <option value="">Toutes les écoles</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">Année Académique</label>
                            <select name="academic_year_id" class="form-control form-control-alternative">
                                <option value="">Toutes les années</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold text-dark">Statut</label>
                            <select name="status" class="form-control form-control-alternative">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Annulée</option>
                                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transférée</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold text-dark">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-alternative" placeholder="N° Inscription, Nom, Matricule..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-round waves-effect btn-block">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° Inscription</th>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $registration->registration_number }}</span></td>
                                        <td class="font-weight-bold">
                                            {{ optional(optional($registration->student)->personne)->nom_complet ?? (optional(optional($registration->student)->personne)->nom . ' ' . optional(optional($registration->student)->personne)->prenoms) }}
                                            <br><small class="text-muted">Matricule: {{ optional($registration->student)->matricule }}</small>
                                        </td>
                                        <td>{{ optional($registration->schoolClass)->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($registration->type === 'inscription')
                                                <span class="badge badge-info px-2 py-1">Nouvelle</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Réinscription</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($registration->registration_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @switch($registration->status)
                                                @case('confirmed') <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Confirmée</span> @break
                                                @case('pending') <span class="badge badge-warning px-2 py-1"><i class="fa fa-clock mr-1"></i>En attente</span> @break
                                                @case('canceled') <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Annulée</span> @break
                                                @default <span class="badge badge-dark px-2 py-1">{{ $registration->status }}</span>
                                            @endswitch
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('schooling.registrations.show', $registration) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('schooling.registrations.edit', $registration) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i> Aucune inscription trouvée.
                                        </td>
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

<!-- Modales AJAX Multi-step -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-file-signature"></i> NOUVELLE INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-id-card"></i> DOSSIER D'INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody1"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" style="max-width: 100% !important; width: 1200px;">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION INSCRIPTION</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
@endsection