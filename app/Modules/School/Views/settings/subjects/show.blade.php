<div class="page-body p-2">
    <div class="row">
        <!-- Informations Matière -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>FICHE DÉTAILLÉE DE LA MATIÈRE
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-book fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">{{ $subject->name }}</h4>
                        @if($subject->code)
                            <span class="badge badge-primary px-3 py-1">{{ $subject->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($subject->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calendar-check-o text-secondary mr-1"></i> Créé le :</strong> 
                            <span class="text-dark">{{ $subject->created_at ? $subject->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-12 mb-2 mt-2">
                            <strong><i class="fa fa-align-left text-secondary mr-1"></i> Description :</strong>
                            <p class="text-muted mt-1 bg-light p-2 rounded">{{ $subject->description ?: 'Aucune description disponible.' }}</p>
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