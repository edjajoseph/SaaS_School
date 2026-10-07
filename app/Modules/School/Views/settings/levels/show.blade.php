<div class="page-body">
    <div class="row">
        <!-- Fiche d'information générale du niveau -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fa fa-info-circle text-primary mr-1"></i>FICHE NIVEAU
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Code :</strong> <span class="badge badge-primary">{{ $level->code }}</span></p>
                    <p><strong>Nom du niveau :</strong> {{ $level->name }}</p>
                    <p><strong>Cycle rattaché :</strong> <span class="badge badge-info">{{ $level->cycle_name }}</span></p>
                    <p><strong>Scolarité par défaut :</strong> <span class="font-weight-bold text-success">{{ number_format($level->tuition_fee, 0, ',', ' ') }} FCFA</span></p>
                    <p><strong>Ordre d'affichage :</strong> {{ $level->sequence_order }}</p>
                    <p>
                        <strong>Examen officiel :</strong> 
                        @if($level->has_exam)
                            <span class="badge badge-warning text-dark"><i class="fa fa-graduation-cap mr-1"></i>{{ $level->exam_name ?? 'Oui' }}</span>
                        @else
                            <span class="text-muted"><i class="fa fa-times mr-1"></i>Aucun</span>
                        @endif
                    </p>
                    <p>
                        <strong>Statut :</strong> 
                        @if($level->is_active)
                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                        @else
                            <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                        @endif
                    </p>
                    <hr>
                    <button type="button" class="btn btn-warning btn-sm btn-round waves-effect" data-dismiss="modal">
                        <i class="fa fa-undo mr-1"></i> {{ __('Fermer') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Classes rattachées -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fa fa-building text-primary mr-1"></i>CLASSES ASSOCIÉES
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="15%">Code</th>
                                    <th>Nom de la classe</th>
                                    <th>Scolarité appliquée</th>
                                    <th width="20%">Capacité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($level->classes as $schoolClass)
                                    <tr>
                                        <td><code>{{ $schoolClass->code ?? '-' }}</code></td>
                                        <td class="font-weight-bold">{{ $schoolClass->name }}</td>
                                        <td class="font-weight-bold text-primary">
                                            {{ number_format($schoolClass->tuition_fee, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td>{{ $schoolClass->capacity ?? 'N/A' }} élève(s)</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fa fa-info-circle mr-1"></i> Aucune classe associée à ce niveau.
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