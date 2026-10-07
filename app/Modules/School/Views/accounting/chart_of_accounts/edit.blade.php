
<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-edit text-warning mr-2"></i>Modifier Compte Comptable</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.accounting.chart-of-accounts.update', $chartOfAccount->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Numéro de Compte</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $chartOfAccount->code) }}" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="font-weight-bold">Intitulé du Compte</label>
                        <input type="text" name="label" class="form-control" value="{{ old('label', $chartOfAccount->label) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Type de Compte</label>
                    <select name="type" class="form-control" required>
                        <option value="asset" {{ $chartOfAccount->type === 'asset' ? 'selected' : '' }}>Actif</option>
                        <option value="liability" {{ $chartOfAccount->type === 'liability' ? 'selected' : '' }}>Passif</option>
                        <option value="equity" {{ $chartOfAccount->type === 'equity' ? 'selected' : '' }}>Capitaux Propres</option>
                        <option value="revenue" {{ $chartOfAccount->type === 'revenue' ? 'selected' : '' }}>Produit</option>
                        <option value="expense" {{ $chartOfAccount->type === 'expense' ? 'selected' : '' }}>Charge</option>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $chartOfAccount->is_active ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold" for="is_active">Activer ce compte</label>
                </div>
                <div class="text-right">
                    <a href="{{ route('school.accounting.chart-of-accounts.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fa fa-sync mr-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>