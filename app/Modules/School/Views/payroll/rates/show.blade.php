@php
    $plannedCm = (float) ($subjectRate->volume_cm ?? 0);
    $plannedTd = (float) ($subjectRate->volume_td ?? 0);
    $plannedTp = (float) ($subjectRate->volume_tp ?? 0);
    $plannedExam = (float) ($subjectRate->volume_examen ?? 0);
    $totalPlanned = (float) ($subjectRate->total_volume ?? ($plannedCm + $plannedTd + $plannedTp + $plannedExam));

    $executedCm = (float) ($subjectRate->executed_volume_cm ?? 0);
    $executedTd = (float) ($subjectRate->executed_volume_td ?? 0);
    $executedTp = (float) ($subjectRate->executed_volume_tp ?? 0);
    $executedExam = (float) ($subjectRate->executed_volume_examen ?? 0);
    $totalExecuted = (float) ($subjectRate->total_executed_hours ?? ($executedCm + $executedTd + $executedTp + $executedExam));

    $percentage = $totalPlanned > 0 ? min(100, round(($totalExecuted / $totalPlanned) * 100, 1)) : 0;
    $isCompleted = $subjectRate->is_completed || ($totalPlanned > 0 && $totalExecuted >= $totalPlanned);
@endphp

<div class="page-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="fa fa-user mr-2"></i>FICHE D'ATTRIBUTION ET D'EXÉCUTION DU COURS
                    </h5>
                    @if($isCompleted)
                        <span class="badge badge-success px-3 py-2 font-weight-bold">
                            <i class="fa fa-check-circle mr-1"></i> COURS TERMINÉ
                        </span>
                    @else
                        <span class="badge badge-warning px-3 py-2 font-weight-bold text-white">
                            <i class="fa fa-spinner fa-spin mr-1"></i> EN COURS D'EXÉCUTION
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Infos Générales -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Enseignant :</strong> {{ $subjectRate->staff->personne->nom_complet ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Matricule :</strong> <span class="badge badge-primary">{{ $subjectRate->staff->staff_code ?? 'N/A' }}</span></p>
                            <p class="mb-0"><strong>Diplôme / Grade :</strong> {{ $subjectRate->staff->degree->name ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Classe :</strong> <span class="badge badge-info">{{ $subjectRate->schoolClass->name ?? 'N/A' }}</span></p>
                            <p class="mb-1"><strong>Matière :</strong> {{ $subjectRate->subject->subject->name ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Statut Tarif :</strong> 
                                @if($subjectRate->is_customized)
                                    <span class="badge badge-warning">Tarif Négocié Spécifique</span>
                                @else
                                    <span class="badge badge-secondary">Calculé selon Grille Globale</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Synthèse d'avancement -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="font-weight-bold text-dark mb-0">
                                    <i class="fa fa-tasks text-primary mr-1"></i> Progression Pédagogique Globale
                                </h6>
                                <span class="font-weight-bold {{ $percentage >= 100 ? 'text-success' : 'text-primary' }}" style="font-size: 1.1rem;">
                                    {{ $totalExecuted }} h / {{ $totalPlanned }} h ({{ $percentage }}%)
                                </span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar {{ $percentage >= 100 ? 'bg-success' : ($percentage > 50 ? 'bg-info' : 'bg-warning') }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%;" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            @if($subjectRate->completed_at)
                                <small class="text-muted mt-2 d-block text-right">
                                    <i class="fa fa-calendar-check-o mr-1"></i> Marqué achevé le : {{ \Carbon\Carbon::parse($subjectRate->completed_at)->format('d/m/Y à H:i') }}
                                </small>
                            @endif
                        </div>
                    </div>

                    <!-- Comparison Volumes Prévus vs Exécutés -->
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fa fa-clock-o text-primary mr-1"></i> Volumes Horaires (Prévus vs Exécutés)
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Type de cours</th>
                                    <th>CM</th>
                                    <th>TD</th>
                                    <th>TP</th>
                                    <th>Examen / CC</th>
                                    <th class="bg-white">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold text-left bg-light">Volume Prévu</td>
                                    <td>{{ $plannedCm }} h</td>
                                    <td>{{ $plannedTd }} h</td>
                                    <td>{{ $plannedTp }} h</td>
                                    <td>{{ $plannedExam }} h</td>
                                    <td class="font-weight-bold text-dark">{{ $totalPlanned }} h</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-left bg-light">Volume Exécuté</td>
                                    <td class="{{ $executedCm >= $plannedCm && $plannedCm > 0 ? 'text-success font-weight-bold' : '' }}">{{ $executedCm }} h</td>
                                    <td class="{{ $executedTd >= $plannedTd && $plannedTd > 0 ? 'text-success font-weight-bold' : '' }}">{{ $executedTd }} h</td>
                                    <td class="{{ $executedTp >= $plannedTp && $plannedTp > 0 ? 'text-success font-weight-bold' : '' }}">{{ $executedTp }} h</td>
                                    <td class="{{ $executedExam >= $plannedExam && $plannedExam > 0 ? 'text-success font-weight-bold' : '' }}">{{ $executedExam }} h</td>
                                    <td class="font-weight-bold {{ $totalExecuted >= $totalPlanned && $totalPlanned > 0 ? 'text-success' : 'text-info' }}">{{ $totalExecuted }} h</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-left bg-light">Reste à faire</td>
                                    <td>{{ max(0, $plannedCm - $executedCm) }} h</td>
                                    <td>{{ max(0, $plannedTd - $executedTd) }} h</td>
                                    <td>{{ max(0, $plannedTp - $executedTp) }} h</td>
                                    <td>{{ max(0, $plannedExam - $executedExam) }} h</td>
                                    <td class="font-weight-bold text-danger">{{ max(0, $totalPlanned - $totalExecuted) }} h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Taux Horaires -->
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fa fa-money text-success mr-1"></i> Taux Horaires Appliqués (FCFA)
                    </h6>
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Taux CM</small>
                                <strong class="text-primary font-weight-bold">{{ number_format($subjectRate->rate_cm, 0, ',', ' ') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Taux TD</small>
                                <strong class="text-primary font-weight-bold">{{ number_format($subjectRate->rate_td, 0, ',', ' ') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Taux TP</small>
                                <strong class="text-primary font-weight-bold">{{ number_format($subjectRate->rate_tp, 0, ',', ' ') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted d-block">Taux Examen</small>
                                <strong class="text-primary font-weight-bold">{{ number_format($subjectRate->rate_examen, 0, ',', ' ') }}</strong>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Supports de cours associés -->
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fa fa-folder-open text-warning mr-1"></i> Supports de cours déposés
                    </h6>
                    @if($subjectRate->materials && $subjectRate->materials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Titre du document</th>
                                        <th>Type</th>
                                        <th>Date de dépôt</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectRate->materials as $material)
                                        <tr>
                                            <td>{{ $material->title }}</td>
                                            <td><span class="badge badge-secondary">{{ strtoupper($material->type) }}</span></td>
                                            <td>{{ $material->uploaded_at ? $material->uploaded_at->format('d/m/Y H:i') : '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="btn btn-primary btn-mini">
                                                    <i class="fa fa-download mr-1"></i> Télécharger
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fa fa-exclamation-triangle mr-1"></i> Aucun support de cours n'a été déposé pour le moment par l'enseignant.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <a href="{{ route('school.accounting.teacher-rates.index') }}" class="btn btn-secondary btn-round waves-effect" >
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </a>
</div>