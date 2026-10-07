<div class="page-body p-2">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light py-2"><h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-briefcase mr-1"></i> DÉTAILS RÔLE</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Code :</strong> <span class="badge badge-primary">{{ $staffRole->code ?? 'N/A' }}</span></p>
                    <p class="mb-0"><strong>Libellé :</strong> {{ $staffRole->name }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="feather icon-file-text mr-1"></i> CONTRATS RATTACHÉS</h5>
                    <span class="badge badge-info">{{ $staffRole->contracts->count() }} contrat(s)</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead><tr><th>Agent</th><th>Poste Exact</th></tr></thead>
                        <tbody>
                            @forelse($staffRole->contracts as $contract)
                                <tr>
                                    <td>{{ $contract->staff->personne->nom_complet ?? 'N/A' }}</td>
                                    <td>{{ $contract->job_title }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucun contrat lié.</td></tr>
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