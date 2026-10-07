<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-edit text-warning mr-2"></i>Modifier Journal Comptable</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.accounting.journals.update', $journal->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="font-weight-bold">Code Journal</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $journal->code) }}" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nom du Journal</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $journal->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Type de Journal</label>
                    <select name="type" class="form-control" required>
                        <option value="bank" {{ old('type', $journal->type) == 'bank' ? 'selected' : '' }}>Banque</option>
                        <option value="cash" {{ old('type', $journal->type) == 'cash' ? 'selected' : '' }}>Caisse</option>
                        <option value="sales" {{ old('type', $journal->type) == 'sales' ? 'selected' : '' }}>Ventes / Scolarité</option>
                        <option value="purchase" {{ old('type', $journal->type) == 'purchase' ? 'selected' : '' }}>Achats</option>
                        <option value="general" {{ old('type', $journal->type) == 'general' ? 'selected' : '' }}>Opérations Diverses (OD)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Compte par Défaut / Contrepartie (Optionnel)</label>
                    <select name="default_account_id" class="form-control select2">
                        <option value="">-- Aucun compte rattaché --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ old('default_account_id', $journal->default_account_id) == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code }} - {{ $acc->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-right">
                    <a href="{{ route('school.accounting.journals.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-sync mr-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>