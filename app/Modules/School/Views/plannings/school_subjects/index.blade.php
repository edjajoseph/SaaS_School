@extends('School::layouts.app3', [
    'namePage' => 'Configuration des Matières Par Établissement',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'school_subjects',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

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
                        <i class="feather icon-list mr-2"></i>MATIÈRES CONFIGURÉES PAR ÉTABLISSEMENT
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('school-subjects.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Affecter une matière">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle Affectation
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

                    <!-- Barre de recherche et filtres -->
                    <form method="GET" action="{{ route('school-subjects.index') }}" class="mb-4">
                        <div class="row">
                            @if(isset($isAdmin) && $isAdmin)
                                <div class="col-md-3">
                                    <select name="school_id" class="form-control">
                                        <option value="">-- Tous les établissements --</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="{{ (isset($isAdmin) && $isAdmin) ? 'col-md-7' : 'col-md-10' }}">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom personnalisé ou code..." value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block waves-effect">
                                    <i class="fa fa-filter mr-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    @if(isset($isAdmin) && $isAdmin)
                                        <th>Établissement</th>
                                    @endif
                                    <th>Matière</th>
                                    <th>Unité d'Enseignement (UE)</th>
                                    <th class="text-center" width="6%">Crédits</th>
                                    <th class="text-center" width="6%">Coeff.</th>
                                    <th class="text-center" width="12%">Volume (CM/TD/TP)</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schoolSubjects as $item)
                                    <tr>
                                        @if(isset($isAdmin) && $isAdmin)
                                            <td>{{ $item->school->name ?? 'N/A' }}</td>
                                        @endif
                                        <td class="font-weight-bold">
                                            @if($item->color_code)
                                                <span class="badge badge-pill mr-1" style="background-color: {{ $item->color_code }};">&nbsp;</span>
                                            @endif
                                            {{ $item->custom_name ?: $item->subject->name }}
                                            @if($item->code)
                                                <small class="text-muted d-block">Code: {{ $item->code }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->teachingUnit->name ?? 'Non associée' }}</td>
                                        <td class="text-center"><span class="badge badge-info px-2 py-1">{{ $item->credits }}</span></td>
                                        <td class="text-center"><span class="badge badge-secondary px-2 py-1">{{ $item->coefficient }}</span></td>
                                        <td class="text-center">
                                            <small class="font-weight-bold">{{ $item->hours_cm }}h CM / {{ $item->hours_td }}h TD / {{ $item->hours_tp }}h TP</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('school-subjects.show', $item) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a class="btn btn-success btn-mini mr-1 text-white" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('school-subjects.edit', $item) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('school-subjects.destroy', $item) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action retirera la matière de cet établissement !',
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
                                        <td colspan="{{ (isset($isAdmin) && $isAdmin) ? '7' : '6' }}" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune matière configurée pour le moment.
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
                <h5 class="modal-title text-primary"><i class="fa fa-graduation-cap"></i> AFFECTER UNE MATIÈRE</h5>
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
                <h5 class="modal-title text-info"><i class="fa fa-info-circle"></i> DÉTAILS MATIÈRE ÉTABLISSEMENT</h5>
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
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION CONFIGURATION</h5>
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