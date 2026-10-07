
<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-plus-circle text-info mr-2"></i>Ajouter un Compte au Plan Comptable</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.accounting.chart-of-accounts.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold">Numéro de Compte</label>
                        <input type="text" name="code" class="form-control" placeholder="ex: 411100" value="{{ old('code') }}" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="font-weight-bold">Intitulé du Compte</label>
                        <input type="text" name="label" class="form-control" placeholder="ex: Clients - Scolarités" value="{{ old('label') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Type de Compte</label>
                    <select name="type" class="form-control" required>
                        <option value="asset">Actif (Classe 2, 3, 4, 5)</option>
                        <option value="liability">Passif (Classe 1, 4, 5)</option>
                        <option value="equity">Capitaux Propres (Classe 1)</option>
                        <option value="revenue">Produit (Classe 7)</option>
                        <option value="expense">Charge (Classe 6)</option>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked>
                    <label class="form-check-label font-weight-bold" for="is_active">Activer ce compte pour les écritures</label>
                </div>
                <div class="text-right">
                    <a href="{{ route('school.accounting.chart-of-accounts.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save mr-1"></i> Enregistrer Compte</button>
                </div>
            </form>
        </div>
    </div>
</div>