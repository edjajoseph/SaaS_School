<div class="page-body p-2">
    <div class="row">
        <!-- Informations Matière Établissement -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>CONFIGURATIVE DE LA MATIÈRE
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">
                            {{ $schoolSubject->custom_name ?: $schoolSubject->subject->name }}
                        </h4>
                        @if($schoolSubject->code)
                            <span class="badge badge-primary px-3 py-1">{{ $schoolSubject->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-building text-secondary mr-1"></i> Établissement :</strong> 
                            <span class="text-dark">{{ $schoolSubject->school->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-layer-group text-secondary mr-1"></i> Unité d'Enseignement :</strong> 
                            <span class="text-dark">{{ $schoolSubject->teachingUnit->name ?? 'Non définie' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-star text-secondary mr-1"></i> Crédits :</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $schoolSubject->credits }} ECTS</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calculator text-secondary mr-1"></i> Coefficient :</strong> 
                            <span class="badge badge-secondary px-2 py-1">{{ $schoolSubject->coefficient }}</span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <strong><i class="fa fa-clock-o text-secondary mr-1"></i> Volume Horaire Réparti :</strong> 
                            <div class="mt-1">
                                <span class="badge badge-light border text-dark mr-1">{{ $schoolSubject->hours_cm }}h Cours Magistral (CM)</span>
                                <span class="badge badge-light border text-dark mr-1">{{ $schoolSubject->hours_td }}h Travaux Dirigés (TD)</span>
                                <span class="badge badge-light border text-dark">{{ $schoolSubject->hours_tp }}h Travaux Pratiques (TP)</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 mt-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($schoolSubject->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>