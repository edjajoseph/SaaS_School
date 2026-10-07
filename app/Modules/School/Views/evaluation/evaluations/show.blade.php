<div class="modal-body p-4">
    <!-- En-tête / Informations Générales -->
    <div class="card border mb-4 shadow-sm">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0 text-primary font-weight-bold">
                <i class="fa fa-info-circle mr-1"></i> {{ __('Détails de l\'évaluation') }}
            </h6>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>{{ __('Intitulé') }} :</strong> {{ $evaluation->title }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>{{ __('Classe') }} :</strong> 
                    <span class="badge badge-primary">{{ $evaluation->schoolClass->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>{{ __('Matière / ECUE') }} :</strong> {{ $evaluation->subject->name ?? 'N/A' }}
                    @if($evaluation->subject && $evaluation->subject->teachingUnit)
                        <span class="badge badge-info ml-1">{{ $evaluation->subject->teachingUnit->code }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-2">
                    <strong>{{ __('Type') }} :</strong> {{ $evaluation->type->name ?? 'N/A' }}
                </div>
                <div class="col-md-3 mb-2">
                    <strong>{{ __('Date') }} :</strong> {{ \Carbon\Carbon::parse($evaluation->evaluated_at)->format('d/m/Y') }}
                </div>
                <div class="col-md-3 mb-2">
                    <strong>{{ __('Barème Max') }} :</strong> /{{ number_format($evaluation->max_score, 2) }}
                </div>
                <div class="col-md-3 mb-2">
                    <strong>{{ __('Coefficient') }} :</strong> {{ number_format($evaluation->coefficient, 2) }}
                </div>
                <div class="col-md-3 mb-2">
                    <strong>{{ __('Statut') }} :</strong> 
                    @if($evaluation->is_published)
                        <span class="badge badge-success"><i class="fa fa-eye mr-1"></i>Publié</span>
                    @else
                        <span class="badge badge-warning"><i class="fa fa-eye-slash mr-1"></i>Brouillon</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau de la Fiche de Notes -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover align-middle mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="15%">Matricule</th>
                    <th>Nom & Prénoms</th>
                    <th width="15%" class="text-center">Note / {{ number_format($evaluation->max_score, 0) }}</th>
                    <th width="15%" class="text-center">Note / 20</th>
                    <th>Appréciation / Observation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $registration)
                    @php
                        $student = $registration->student;
                        $personne = $student->personne ?? null;
                        
                        // Récupération de la note via la collection indexée
                        $grade = $existingGrades->get($registration->id);
                        $score = $grade ? $grade->score : null;
                        
                        // Calcul de la note ramenée sur 20
                        $scoreOn20 = ($score !== null && $evaluation->max_score > 0) 
                            ? ($score / $evaluation->max_score) * 20 
                            : null;
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge badge-secondary">{{ $student->matricule ?? 'N/A' }}</span>
                        </td>
                        <td class="font-weight-bold text-uppercase">
                            {{ $personne ? $personne->nom . ' ' . $personne->prenoms : 'N/A' }}
                        </td>
                        <td class="text-center font-weight-bold">
                            @if($score !== null)
                                <span class="{{ $scoreOn20 < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($score, 2) }}
                                </span>
                            @else
                                <span class="text-muted font-italic">Non saisie</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($scoreOn20 !== null)
                                <span class="badge {{ $scoreOn20 < 10 ? 'badge-danger' : 'badge-success' }} px-2 py-1">
                                    {{ number_format($scoreOn20, 2) }} / 20
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $grade->remarks ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa fa-info-circle mr-1"></i> Aucun étudiant inscrit dans cette classe.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pied de page de la modale -->
<div class="modal-footer bg-light d-flex justify-content-between align-items-center">
    <a href="{{ route('evaluation.evaluations.index') }}" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </a>

    <!-- Bouton Imprimer PDF (Toujours accessible) -->
    <a href="{{ route('evaluation.evaluations.print-pdf', $evaluation) }}" target="_blank" class="btn btn-danger btn-round waves-effect shadow-sm mr-1">
        <i class="fa fa-file-pdf-o mr-1"></i> {{ __('Imprimer PDF') }}
    </a>

    <div>        
        {{-- Le bouton est masqué aux enseignants si l'évaluation est publiée --}}
        @if($canEditGrades)
            <a href='#' data-toggle="modal" 
                id="mediumButton1" 
                data-target="#mediumModal1" 
                data-attr="{{ route('evaluation.evaluations.grades.grid', $evaluation) }}" 
                class="btn btn-primary btn-round waves-effect shadow-sm" 
                title="Saisir les Notes">
                <i class="fa fa-pencil-square-o mr-1"></i> {{ __('Saisir / Modifier les Notes') }}
            </a>
        @endif
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

<script>
    (function($) {
        $(document).off('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]')
                   .on('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function(e) {
            e.preventDefault();
            var $modal = $(this).closest('.modal');
            if ($modal.length) {
                $modal.modal('hide');
            } else {
                $('.modal.show, .modal.in').modal('hide');
            }
        });
    })(jQuery);
</script>