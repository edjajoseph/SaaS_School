<div class="page-body">
    <div class="row">
        <!-- Fiche d'information générale -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fa fa-info-circle text-primary mr-1"></i>FICHE DÉCOUPAGE
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Code :</strong> <span class="badge badge-primary">{{ $periodType->code }}</span></p>
                    <p><strong>Intitulé :</strong> {{ $periodType->name }}</p>
                    <p><strong>Description :</strong> {{ $periodType->description ?: 'Aucune description' }}</p>
                    <p>
                        <strong>Statut :</strong> 
                        @if($periodType->is_active ?? true)
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

        <!-- Sous-périodes associées -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fa fa-list-ol text-primary mr-1"></i>ÉLÉMENTS ASSOCIÉS (SOUS-PÉRIODES)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="15%">Ordre</th>
                                    <th width="25%">Code</th>
                                    <th>Nom de la période</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($periodType->items as $item)
                                    <tr>
                                        <td class="font-weight-bold text-center">{{ $item->sequence_order }}</td>
                                        <td><code>{{ $item->code ?? '-' }}</code></td>
                                        <td class="font-weight-bold">{{ $item->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            <i class="fa fa-info-circle mr-1"></i> Aucun élément rattaché à ce type de découpage.
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