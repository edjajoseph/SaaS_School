<div class="page-body p-2">
    <div class="row">
        <!-- Informations Type de Document -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>FICHE DÉTAILLÉE DU TYPE DE DOCUMENT
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-file-text-o fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">{{ $documentType->name }}</h4>
                        @if($documentType->code)
                            <span class="badge badge-primary px-3 py-1">{{ $documentType->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-exclamation-circle text-secondary mr-1"></i> Obligation :</strong> 
                            @if($documentType->is_required)
                                <span class="badge badge-danger px-2 py-1">Obligatoire</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Optionnel</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($documentType->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-folder text-secondary mr-1"></i> Documents associés :</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $documentType->student_documents_count ?? $documentType->studentDocuments()->count() }} document(s)</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calendar-check-o text-secondary mr-1"></i> Créé le :</strong> 
                            <span class="text-dark">{{ $documentType->created_at ? $documentType->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
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