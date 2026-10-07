<div class="page-body">
    <div class="row">
        <!-- Informations Générales -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fa fa-info-circle mr-1"></i> INFORMATIONS DE LA MATIÈRE</h5>
                </div>
                <div class="card-body">
                    <p><strong>Établissement :</strong> {{ $schoolSubject->school->name ?? 'N/A' }}</p>
                    <p><strong>Matière de référence :</strong> {{ $schoolSubject->subject->name ?? 'N/A' }}</p>
                    <p><strong>Nom personnalisé :</strong> {{ $schoolSubject->custom_name ?: 'Aucun' }}</p>
                    <p><strong>Code :</strong> <span class="badge badge-primary" style="background-color: {{ $schoolSubject->color_code }}">{{ $schoolSubject->code ?? 'N/A' }}</span></p>
                    <p><strong>Unité d'Enseignement :</strong> {{ $schoolSubject->teachingUnit->name ?? 'Aucune' }}</p>
                    <p><strong>Statut :</strong> 
                        @if($schoolSubject->is_active)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-danger">Inactif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Volume Horaire et Crédits -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fa fa-clock-o mr-1"></i> PARAMÈTRES ACADÉMIQUES</h5>
                </div>
                <div class="card-body">
                    <p><strong>Crédits ECTS :</strong> <span class="font-weight-bold text-success">{{ $schoolSubject->credits }}</span></p>
                    <p><strong>Coefficient :</strong> <span class="font-weight-bold text-info">{{ $schoolSubject->coefficient }}</span></p>
                    <hr>
                    <p><strong>Volume CM (Cours Magistral) :</strong> {{ $schoolSubject->hours_cm }} heures</p>
                    <p><strong>Volume TD (Travaux Dirigés) :</strong> {{ $schoolSubject->hours_td }} heures</p>
                    <p><strong>Volume TP (Travaux Pratiques) :</strong> {{ $schoolSubject->hours_tp }} heures</p>
                    <p><strong>Volume Horaire Total :</strong> <span class="badge badge-warning text-dark">{{ $schoolSubject->hours_cm + $schoolSubject->hours_td + $schoolSubject->hours_tp }} heures</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary btn-round waves-effect" onclick="$(this).closest('.modal').modal('hide');">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>