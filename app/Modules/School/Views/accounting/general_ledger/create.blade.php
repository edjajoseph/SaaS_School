
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-pen-nib text-primary mr-2"></i>Nouvelle Pièce Comptable
                </h4>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('school.accounting.entries.store') }}" method="POST">
                    @csrf

                    <!-- En-tête de la Pièce -->
                    <div class="form-row mb-3">
                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">Exercice Comptable</label>
                            <select name="fiscal_year_id" class="form-control" required>
                                @foreach($fiscalYears as $fy)
                                    <option value="{{ $fy->id }}">{{ $fy->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">Journal</label>
                            <select name="journal_id" class="form-control" required>
                                @foreach($journals as $j)
                                    <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">N° Pièce / Référence</label>
                            <input type="text" name="entry_number" class="form-control" value="OD-{{ date('Ym-His') }}" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">Date de Pièce</label>
                            <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Libellé de l'Opération</label>
                        <input type="text" name="label" class="form-control" placeholder="ex: Opération de régularisation ou achat de fournitures" required>
                    </div>

                    <hr>

                    <!-- Lignes de Débit / Crédit (Partie Double) -->
                    <h5 class="font-weight-bold text-dark mb-3">Lignes d'Écritures (Équilibrées)</h5>
                    
                    <table class="table table-bordered" id="items-table">
                        <thead class="thead-light">
                            <tr>
                                <th width="40%">Compte Comptable</th>
                                <th width="25%">Libellé Ligne</th>
                                <th width="15%" class="text-right">Débit (FCFA)</th>
                                <th width="15%" class="text-right">Crédit (FCFA)</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <tr>
                                <td>
                                    <select name="items[0][chart_of_account_id]" class="form-control" required>
                                        <option value="">-- Choisir un compte --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[0][label]" class="form-control" placeholder="Optionnel"></td>
                                <td><input type="number" step="0.01" name="items[0][debit]" class="form-control text-right calc-debit" value="0.00" onchange="calculateTotals()"></td>
                                <td><input type="number" step="0.01" name="items[0][credit]" class="form-control text-right calc-credit" value="0.00" onchange="calculateTotals()"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="items[1][chart_of_account_id]" class="form-control" required>
                                        <option value="">-- Choisir un compte --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[1][label]" class="form-control" placeholder="Optionnel"></td>
                                <td><input type="number" step="0.01" name="items[1][debit]" class="form-control text-right calc-debit" value="0.00" onchange="calculateTotals()"></td>
                                <td><input type="number" step="0.01" name="items[1][credit]" class="form-control text-right calc-credit" value="0.00" onchange="calculateTotals()"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right font-weight-bold">Totaux & Équilibre :</td>
                                <td class="text-right font-weight-bold text-success" id="total-debit-display">0.00 FCFA</td>
                                <td class="text-right font-weight-bold text-danger" id="total-credit-display">0.00 FCFA</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-row">
                        <i class="fas fa-plus mr-1"></i> Ajouter une ligne
                    </button>

                    <div class="text-right">
                        <a href="{{ route('school.accounting.entries.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Valider la Pièce</button>
                    </div>
                </form>
            </div>
        </div>
    

<script>
let rowIndex = 2;

document.getElementById('add-row').addEventListener('click', function() {
let row = `
    <tr>
        <td>
            <select name="items[${rowIndex}][chart_of_account_id]" class="form-control" required>
                <option value="">-- Choisir un compte --</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->label }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="items[${rowIndex}][label]" class="form-control" placeholder="Optionnel"></td>
        <td><input type="number" step="0.01" name="items[${rowIndex}][debit]" class="form-control text-right calc-debit" value="0.00" onchange="calculateTotals()"></td>
        <td><input type="number" step="0.01" name="items[${rowIndex}][credit]" class="form-control text-right calc-credit" value="0.00" onchange="calculateTotals()"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
    </tr>
`;
document.getElementById('items-body').insertAdjacentHTML('beforeend', row);
rowIndex++;
});

document.addEventListener('click', function(e) {
if (e.target.closest('.remove-row')) {
    e.target.closest('tr').remove();
    calculateTotals();
}
});

function calculateTotals() {
let totalDebit = 0;
let totalCredit = 0;

document.querySelectorAll('.calc-debit').forEach(input => totalDebit += parseFloat(input.value || 0));
document.querySelectorAll('.calc-credit').forEach(input => totalCredit += parseFloat(input.value || 0));

document.getElementById('total-debit-display').innerText = totalDebit.toLocaleString('fr-FR') + ' FCFA';
document.getElementById('total-credit-display').innerText = totalCredit.toLocaleString('fr-FR') + ' FCFA';
}
</script>