<div class="page-body p-2">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light py-2"><h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-globe mr-1"></i> DÉTAILS PAYS</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Pays :</strong> {{ $country->name }}</p>
                    <p class="mb-2"><strong>Nationalité :</strong> {{ $country->nationality }}</p>
                    <p class="mb-2"><strong>Codes ISO :</strong> <span class="badge badge-primary">{{ $country->iso_code_2 }}</span> / <span class="badge badge-secondary">{{ $country->iso_code_3 ?: 'N/A' }}</span></p>
                    <p class="mb-0"><strong>Indicatif :</strong> {{ $country->phone_code ?: 'Non renseigné' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-users mr-1"></i> CITOYENS / PERSONNES</h5>
                    <span class="badge badge-info">{{ $country->personnes->count() }} personne(s)</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead><tr><th>Nom & Prénoms</th><th>Email</th></tr></thead>
                        <tbody>
                            @forelse($country->personnes as $personne)
                                <tr>
                                    <td>{{ $personne->nom_complet }}</td>
                                    <td>{{ $personne->email ?: 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucune personne enregistrée avec cette nationalité.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer bg-light mt-3">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Fermer</button>
</div>