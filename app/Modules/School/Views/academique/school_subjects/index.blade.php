@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Matières de l\'Établissement',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'academic.school-subjects',
    'activeModule' => 'planning',
])

@section('content')
<style>
hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-book mr-2"></i>MATIÈRES CONFIGURÉES
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('academic.school-subjects.create', ['school_id' => request('school_id')]) }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Ajouter une matière">
                        <i class="ace-icon fa fa-plus mr-1"></i> Ajouter une Matière
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')
                    <!-- Notification SweetAlert Flash Session -->
                    <script class="alert alert-success" role="alert">
                        @if($message = session('success'))
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '{{ $message }}', confirmButtonColor: '#1ab394' });
                        @elseif($message = session('warning'))
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '{{ $message }}', confirmButtonColor: '#f8ac59' });
                        @elseif($message = session('error'))
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '{{ $message }}', confirmButtonColor: '#ed5565' });
                        @endif
                    </script>

                    <!-- Zone de Filtrage -->
                    <form method="GET" action="{{ route('academic.school-subjects.index') }}" class="row mb-4">
                        <div class="col-md-4">
                            <select name="school_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Tous les établissements --</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par code, nom personnalisé..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex">
                            <button type="submit" class="btn btn-secondary mr-2"><i class="fa fa-search"></i> Filtrer</button>
                            <a href="{{ route('academic.school-subjects.index') }}" class="btn btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="8%">Code</th>
                                    <th>Matière / Intitulé</th>
                                    <th>Unité d'Enseignement</th>
                                    <th>Établissement</th>
                                    <th width="5%" class="text-center">Crédits</th>
                                    <th width="5%" class="text-center">Coeff.</th>
                                    <th width="10%" class="text-center">CM / TD / TP</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schoolSubjects as $item)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary px-2 py-1" style="background-color: {{ $item->color_code ?? '#4099ff' }}">
                                                {{ $item->code ?? ($item->subject->code ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ $item->custom_name ?: ($item->subject->name ?? 'N/A') }}
                                            @if($item->custom_name)
                                                <br><small class="text-muted">Standard: {{ $item->subject->name ?? 'N/A' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->teachingUnit)
                                                <span class="badge badge-info px-2 py-1">
                                                    <i class="fa fa-book mr-1"></i>{{ $item->teachingUnit->name }}
                                                </span>
                                            @else
                                                <span class="text-muted"><em>Aucune</em></span>
                                            @endif
                                        </td>
                                        <td>{{ $item->school->name ?? 'N/A' }}</td>
                                        <td class="text-center font-weight-bold">{{ $item->credits }}</td>
                                        <td class="text-center font-weight-bold">{{ $item->coefficient }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-warning text-dark px-2 py-1">
                                                {{ $item->hours_cm }}h / {{ $item->hours_td }}h / {{ $item->hours_tp }}h
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('academic.school-subjects.show', $item) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('academic.school-subjects.edit', $item) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="{{ route('academic.school-subjects.destroy', $item) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette matière !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune matière configurée pour cet établissement.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $schoolSubjects->links() }}
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-book"></i> AJOUTER UNE MATIÈRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-info-circle"></i> DÉTAILS DE LA MATIÈRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER LA MATIÈRE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
@endsection