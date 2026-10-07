<div class="row">
    <div class="col-md-6 mb-3">
        <label class="font-weight-bold text-muted d-block mb-1">Intitulé du Plan :</label>
        <span class="h6 font-weight-bold text-primary">{{ $feePlan->name }}</span>
    </div>
    <div class="col-md-6 mb-3">
        <label class="font-weight-bold text-muted d-block mb-1">Année Académique :</label>
        <span class="badge badge-inverse px-2 py-1">
            <i class="fa fa-calendar mr-1"></i>{{ $feePlan->academicYear->name ?? 'N/A' }}
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="font-weight-bold text-muted d-block mb-1">Cible :</label>
        @if($feePlan->schoolClass)
            <span class="badge badge-info px-2 py-1"><i class="fa fa-graduation-cap mr-1"></i>{{ $feePlan->schoolClass->name }}</span>
        @elseif($feePlan->level)
            <span class="badge badge-secondary px-2 py-1"><i class="fa fa-layer-group mr-1"></i>{{ $feePlan->level->name }}</span>
        @else
            <span class="text-muted"><em>Global (Toutes classes)</em></span>
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="font-weight-bold text-muted d-block mb-1">Montant Total :</label>
        <span class="h6 font-weight-bold text-success">{{ number_format($feePlan->total_amount, 0, ',', ' ') }} FCFA</span>
    </div>
    <div class="col-md-4 mb-3">
        <label class="font-weight-bold text-muted d-block mb-1">Statut :</label>
        @if($feePlan->is_active)
            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
        @else
            <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
        @endif
    </div>
</div>

<hr class="hr-gradient">

<h5 class="mb-3 text-primary font-weight-bold">
    <i class="feather icon-list mr-2"></i>DÉTAIL DES TRANCHES DE PAIEMENT
</h5>

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="thead-light">
            <tr>
                <th width="8%">#</th>
                <th>Libellé de la tranche</th>
                <th width="25%">Montant</th>
                <th width="20%">Date Limite</th>
                <th width="15%" class="text-center">Bloquant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feePlan->items as $index => $item)
                <tr>
                    <td class="font-weight-bold text-center">{{ $index + 1 }}</td>
                    <td class="font-weight-bold">{{ $item->label }}</td>
                    <td class="text-primary font-weight-bold">{{ number_format($item->amount, 0, ',', ' ') }} FCFA</td>
                    <td>
                        <i class="fa fa-calendar-alt text-muted mr-1"></i>
                        {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        @if($item->is_blocking)
                            <span class="badge badge-danger px-2 py-1">Oui</span>
                        @else
                            <span class="badge badge-secondary px-2 py-1">Non</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Aucune tranche n'est associée à ce plan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-footer px-0 pb-0 pt-3">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
</div>
