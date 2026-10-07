<div class="page-body p-2">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light py-2"><h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-award mr-1"></i> DÉTAILS DIPLÔME</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Code :</strong> <span class="badge badge-primary">{{ $degree->code ?? 'N/A' }}</span></p>
                    <p class="mb-2"><strong>Libellé :</strong> {{ $degree->name }}</p>
                    <p class="mb-0"><strong>Rang :</strong> {{ $degree->level_rank }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-users mr-1"></i> AGENTS DÉTENTEURS</h5>
                    <span class="badge badge-info">{{ $degree->staff->count() }} agent(s)</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead><tr><th>Matricule</th><th>Nom & Prénoms</th></tr></thead>
                        <tbody>
                            @forelse($degree->staff as $agent)
                                <tr>
                                    <td><span class="badge badge-outline-primary">{{ $agent->staff_code }}</span></td>
                                    <td>{{ $agent->personne->nom_complet ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucun agent rattaché.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer bg-light mt-3">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> {{ __('Fermer') }}</button>
</div>