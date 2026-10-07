<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-plus-circle text-success mr-2"></i>Nouveau Journal Comptable</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.accounting.journals.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Code Journal (ex: VT, CA, BQ, OD)</label>
                    <input type="text" name="code" class="form-control" placeholder="ex: BQ1" value="{{ old('code') }}" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nom du Journal</label>
                    <input type="text" name="name" class="form-control" placeholder="ex: Journal de Banque Principal" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Type de Journal</label>
                    <select name="type" class="form-control" required>
                        <option value="">-- Sélectionner le type --</option>
                        <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Banque</option>
                        <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Caisse</option>
                        <option value="sales" {{ old('type') == 'sales' ? 'selected' : '' }}>Ventes / Scolarité</option>
                        <option value="purchase" {{ old('type') == 'purchase' ? 'selected' : '' }}>Achats</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Opérations Diverses (OD)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Compte par Défaut / Contrepartie (Optionnel)</label>
                    <select name="default_account_id" class="form-control select2">
                        <option value="">-- Aucun compte rattaché --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('default_account_id') == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code }} - {{ $acc->label }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Recommandé pour les journaux de Banque (ex: 521...) et Caisse (ex: 571...).</small>
                </div>

                <div class="text-right">
                    <a href="{{ route('school.accounting.journals.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Enregistrer Journal</button>
                </div>
            </form>
        </div>
    </div>
</div>