<div class="page-body">
    <div class="row">
        <!-- Carte Infos UE -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fa fa-info-circle mr-1"></i> FICHE UE</h5>
                </div>
                <div class="card-body">
                    <p><strong>Code :</strong> <span class="badge badge-primary">{{ $teachingUnit->code }}</span></p>
                    <p><strong>Intitulé :</strong> {{ $teachingUnit->name }}</p>
                    <p><strong>Établissement :</strong> {{ $teachingUnit->school->name ?? 'N/A' }}</p>
                    <p><strong>Niveau :</strong> {{ $teachingUnit->level->name ?? 'N/A' }}</p>
                    <p><strong>Filière / Série :</strong> {{ $teachingUnit->serie->name ?? 'Non renseignée' }}</p>
                    <p><strong>Type :</strong> {{ $teachingUnit->type ?: 'Non renseigné' }}</p>
                    <p><strong>Crédits :</strong> <span class="font-weight-bold text-success">{{ $teachingUnit->credits }}</span></p>
                    <p><strong>Coefficient :</strong> <span class="font-weight-bold text-info">{{ $teachingUnit->coefficient }}</span></p>
                    <p><strong>Volume Horaire Total :</strong> <span class="badge badge-warning text-dark">{{ $teachingUnit->total_hours }} heures</span></p>
                    <hr>
                    <button type="button" class="btn btn-secondary btn-sm btn-round waves-effect" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Tableau des ECUE / Matières rattachées -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-list mr-1"></i> MATIÈRES COMPOSANTES (ECUE)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Matière / ECUE</th>
                                    <th class="text-center">CM</th>
                                    <th class="text-center">TD</th>
                                    <th class="text-center">TP</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachingUnit->schoolsubjects as $subject)
                                    <tr>
                                        <td><span class="badge badge-secondary">{{ $subject->code }}</span></td>
                                        <td class="font-weight-bold">{{ $subject->name }}</td>
                                        <td class="text-center">{{ $subject->hours_cm }}h</td>
                                        <td class="text-center">{{ $subject->hours_td }}h</td>
                                        <td class="text-center">{{ $subject->hours_tp }}h</td>
                                        <td class="text-center font-weight-bold text-primary">
                                            {{ $subject->hours_cm + $subject->hours_td + $subject->hours_tp }}h
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Aucune matière (ECUE) associée à cette Unité d'Enseignement pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <a href="{{ route('academic.teaching-units.index') }}" class="btn btn-secondary btn-round waves-effect">
                            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>