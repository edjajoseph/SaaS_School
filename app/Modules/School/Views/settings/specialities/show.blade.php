<div class="page-body p-2">
    <div class="row">
        <!-- Informations Générales de la Spécialité -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-bookmark mr-1"></i> DÉTAILS DE LA SPÉCIALITÉ
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Code :</strong> 
                        <span class="badge badge-primary px-2 py-1">{{ $speciality->code ?? 'Non défini' }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Libellé :</strong> 
                        <span class="font-weight-bold text-dark">{{ $speciality->name }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Établissement d'attache :</strong> 
                        <span class="badge badge-secondary">{{ $speciality->school->name ?? 'Global / Tous les établissements' }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Date de création :</strong> 
                        {{ $speciality->created_at ? $speciality->created_at->format('d/m/Y à H:i') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Personnel associé à cette Spécialité -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-users mr-1"></i> PERSONNEL RATTACHÉ
                    </h5>
                    <span class="badge badge-info px-2 py-1">
                        {{ $speciality->staff->count() }} agent(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Diplôme</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($speciality->staff as $agent)
                                    <tr>
                                        <td>
                                            <span class="badge badge-outline-primary">{{ $agent->staff_code }}</span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ $agent->personne->nom_complet ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $agent->degree->name ?? 'Non spécifié' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun membre du personnel n'est actuellement rattaché à cette spécialité.
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

<!-- Pied de page de la modale -->
<div class="modal-footer bg-light mt-3">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>