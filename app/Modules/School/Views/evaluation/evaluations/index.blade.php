@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Évaluations',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'evaluation.evaluations',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-check-square mr-2"></i>PROGRAMMATION DES ÉVALUATIONS
                    </h4>
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('evaluation.evaluations.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle Évaluation
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

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Classe</th>
                                    <th>Matière / ECUE</th>
                                    <th>UE</th>
                                    <th>Type & Intitulé</th>
                                    <th>Coeff.</th>
                                    <th>Max</th>
                                    <th>Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr id="evaluation-row-{{ $evaluation->id }}">
                                        <td class="font-weight-bold">{{ $evaluation->schoolClass->name }}</td>
                                        <td>{{ $evaluation->subject->subject->name }}</td>
                                        <td>
                                            @if($evaluation->subject->teachingUnit)
                                                <span class="badge badge-info">{{ $evaluation->subject->teachingUnit->code }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary mr-1">{{ $evaluation->type->code }}</span>
                                            {{ $evaluation->title }}
                                        </td>
                                        <td class="font-weight-bold">{{ number_format($evaluation->coefficient, 2) }}</td>
                                        <td>/{{ number_format($evaluation->max_score, 0) }}</td>
                                        <td class="status-cell">
                                            @if($evaluation->is_published)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-eye mr-1"></i>Publié</span>
                                            @else
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-eye-slash mr-1"></i>Brouillon</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                
                                                {{-- Définition de la condition d'accès aux actions restreintes --}}
                                                @php
                                                    $canAction = !$evaluation->is_published || $canManagePublished;
                                                @endphp

                                                <!-- Bouton Publier / Depublier (Masqué si publié ET enseignant) -->
                                                @if($canAction)
                                                <form action="{{ route('evaluation.evaluations.toggle-publish', $evaluation) }}" method="POST" class="d-inline mr-1"
                                                    onsubmit="
                                                        event.preventDefault(); 
                                                        const form = this;
                                                        if (typeof Swal !== 'undefined') {
                                                            Swal.fire({
                                                                title: 'Changer le statut ?',
                                                                text: 'Voulez-vous vraiment {{ $evaluation->is_published ? 'Masquer les notes' : 'Publier les notes' }} ?',
                                                                icon: 'question',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '{{ $evaluation->is_published ? '#f8ac59' : '#1ab394' }}',
                                                                cancelButtonColor: '#6c757d',
                                                                confirmButtonText: 'Oui, {{ $evaluation->is_published ? 'Masquer' : 'Publier' }} !',
                                                                cancelButtonText: 'Annuler'
                                                            }).then((result) => { 
                                                                if (result.isConfirmed) form.submit(); 
                                                            });
                                                        } else {
                                                            if (confirm('Voulez-vous vraiment {{ $evaluation->is_published ? 'Masquer les notes' : 'Publier les notes' }} ?')) {
                                                                form.submit();
                                                            }
                                                        }
                                                    ">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $evaluation->is_published ? 'btn-inverse' : 'btn-success' }} btn-mini mr-1" title="{{ $evaluation->is_published ? 'Masquer' : 'Publier' }}">
                                                        <i class="fa {{ $evaluation->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i> {{ $evaluation->is_published ? 'Masquer Notes' : 'Publier Notes' }}
                                                    </button>
                                                </form>
                                                

                                                    <!-- Bouton Saisir Notes (Toujours accessible) -->
                                                    <a href='#' data-toggle="modal" 
                                                    id="mediumButton1" 
                                                    data-target="#mediumModal1" 
                                                    data-attr="{{ route('evaluation.evaluations.grades.grid', $evaluation) }}" 
                                                    class="btn btn-primary btn-mini mr-1" 
                                                    title="Saisir les Notes">
                                                        <i class="fa fa-pencil-square-o"></i>Saisir Notes
                                                    </a>
                                                @endif

                                                <!-- Bouton Aperçu/Fiche (Toujours accessible) -->
                                                <a data-toggle="modal" 
                                                id="mediumButton1" 
                                                data-target="#mediumModal1" 
                                                data-attr="{{ route('evaluation.evaluations.show', $evaluation) }}" 
                                                class="btn btn-warning btn-mini mr-1 text-white" 
                                                title="Fiche">
                                                    <i class="fa fa-file-text-o"></i>Fiche Notes
                                                </a>

                                                <!-- Bouton Modifier (Masqué si publié ET enseignant) -->
                                                @if($canAction)
                                                    <a data-toggle="modal" 
                                                    id="mediumButton2" 
                                                    data-target="#mediumModal2" 
                                                    data-attr="{{ route('evaluation.evaluations.edit', $evaluation) }}" 
                                                    class="btn btn-info btn-mini mr-1 text-white" 
                                                    title="Modifier l'évaluation">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                <!-- Bouton Supprimer (Masqué si publié ET enseignant) -->
                                                @if($canAction)
                                                    <form action="{{ route('evaluation.evaluations.destroy', $evaluation) }}" method="POST" class="d-inline" 
                                                        onsubmit="event.preventDefault(); Swal.fire({ title: 'Supprimer ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ed5565', confirmButtonText: 'Oui' }).then((r) => { if (r.isConfirmed) this.submit(); });">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-mini" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Aucune évaluation programmée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $evaluations->links() }}</div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success"><h5 class="modal-title text-primary"><i class="fa fa-file-text-o"></i> ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-file-text-o"></i></i> FICHE DE L'ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody1"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary"><h5 class="modal-title text-success"><i class="fa fa-file-text-o"></i> ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestionnaire AJAX du bouton Publier / Masquer
        $(document).on('click', '.toggle-publish-btn', function (e) {
            e.preventDefault();
            let button = $(this);
            let url = button.data('url');
            let row = button.closest('tr');
            let statusCell = row.find('.status-cell');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Notifications SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Mise à jour de l'affichage du statut et du bouton
                        if (response.is_published) {
                            statusCell.html('<span class="badge badge-success px-2 py-1"><i class="fa fa-eye mr-1"></i>Publié</span>');
                            button.removeClass('btn-success').addClass('btn-inverse')
                                  .attr('title', 'Masquer les notes')
                                  .find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                        } else {
                            statusCell.html('<span class="badge badge-warning px-2 py-1"><i class="fa fa-eye-slash mr-1"></i>Brouillon</span>');
                            button.removeClass('btn-inverse').addClass('btn-success')
                                  .attr('title', 'Publier les notes')
                                  .find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                        }
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la modification du statut.'
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection