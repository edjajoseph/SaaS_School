<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 font-weight-bold text-dark">
                <i class="fa fa-pen-o text-primary mr-2"></i>Nouvelle Pièce Comptable
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
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Exercice Comptable</label>
                        <select name="fiscal_year_id" class="form-control" required>
                            @foreach($fiscalYears as $fy)
                                <option value="{{ $fy->id }}">{{ $fy->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Journal</label>
                        <select name="journal_id" class="form-control" required>
                            @foreach($journals as $j)
                                <option value="{{ $j->id }}">{{ $j->code }} - {{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">N° Pièce / Référence</label>
                        <input type="text" name="entry_number" class="form-control" value="OD-{{ date('Ym-His') }}" required>
                    </div>

                    <div class="form-group col-md-6">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold text-dark mb-0">Lignes d'Écritures (Équilibrées)</h5>
                    <span id="balance-status" class="badge badge-soft-danger px-3 py-2 font-weight-bold">
                        <i class="fa fa-exclamation-circle mr-1"></i> Écritures déséquilibrées
                    </span>
                </div>
                
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
                        <!-- AJOUT DE LA CLASSE item-row SUR LES TR STATIQUES -->
                        <tr class="item-row">
                            <td>
                                <select name="items[0][chart_of_account_id]" class="form-control" required>
                                    <option value="">-- Choisir un compte --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="items[0][label]" class="form-control" placeholder="Optionnel"></td>
                            <td><input type="number" step="0.01" name="items[0][debit]" class="form-control text-right calc-debit" value="0.00"></td>
                            <td><input type="number" step="0.01" name="items[0][credit]" class="form-control text-right calc-credit" value="0.00"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <tr class="item-row">
                            <td>
                                <select name="items[1][chart_of_account_id]" class="form-control" required>
                                    <option value="">-- Choisir un compte --</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="items[1][label]" class="form-control" placeholder="Optionnel"></td>
                            <td><input type="number" step="0.01" name="items[1][debit]" class="form-control text-right calc-debit" value="0.00"></td>
                            <td><input type="number" step="0.01" name="items[1][credit]" class="form-control text-right calc-credit" value="0.00"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-times"></i></button></td>
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
                    <i class="fa fa-plus mr-1"></i> Ajouter une ligne
                </button>

                <div class="text-right">
                    <a href="{{ route('school.accounting.entries.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4" id="btn-submit"><i class="fa fa-save mr-1"></i> Valider la Pièce</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var rowIndex = {{ count($oldItems ?? [1, 2]) }};
    var accounts = @json($accounts);

    var optionsHtml = '<option value="">-- Choisir un compte --</option>';
    accounts.forEach(function(acc) {
        optionsHtml += '<option value="' + acc.id + '">' + acc.code + ' - ' + acc.label + '</option>';
    });

    // Ajouter une ligne
    $(document).off('click', '#add-row').on('click', '#add-row', function(e) {
        e.preventDefault();
        
        var row = `
            <tr class="item-row">
                <td>
                    <select name="items[${rowIndex}][chart_of_account_id]" class="form-control item-account" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td><input type="text" name="items[${rowIndex}][label]" class="form-control" placeholder="Optionnel"></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][debit]" class="form-control text-right calc-debit" value="0.00"></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][credit]" class="form-control text-right calc-credit" value="0.00"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Supprimer">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#items-body').append(row);
        rowIndex++;
        calculateTotals();
    });

    // Suppression d'une ligne (Compte toutes les lignes du tbody)
    $(document).off('click', '.remove-row').on('click', '.remove-row', function(e) {
        e.preventDefault();
        var totalRows = $('#items-body tr').length;
        
        if (totalRows > 2) {
            $(this).closest('tr').remove();
            calculateTotals();
        } else {
            alert('Une pièce comptable doit comporter au moins deux lignes.');
        }
    });

    // Gestion de la saisie exclusive Débit / Crédit
    $(document).off('input', '.calc-debit, .calc-credit').on('input', '.calc-debit, .calc-credit', function() {
        var $tr = $(this).closest('tr');
        if ($(this).hasClass('calc-debit') && parseFloat($(this).val()) > 0) {
            $tr.find('.calc-credit').val('0.00');
        } else if ($(this).hasClass('calc-credit') && parseFloat($(this).val()) > 0) {
            $tr.find('.calc-debit').val('0.00');
        }
        calculateTotals();
    });

    // Calcul des totaux et vérification de l'équilibre
    function calculateTotals() {
        var totalDebit = 0;
        var totalCredit = 0;

        $('.calc-debit').each(function() {
            totalDebit += parseFloat($(this).val()) || 0;
        });

        $('.calc-credit').each(function() {
            totalCredit += parseFloat($(this).val()) || 0;
        });

        $('#total-debit-display').text(Math.round(totalDebit).toLocaleString('fr-FR') + ' FCFA');
        $('#total-credit-display').text(Math.round(totalCredit).toLocaleString('fr-FR') + ' FCFA');

        var isBalanced = totalDebit > 0 && Math.abs(totalDebit - totalCredit) < 0.01;

        if (isBalanced) {
            $('#balance-status')
                .attr('class', 'badge badge-soft-success px-3 py-2 font-weight-bold')
                .html('<i class="fa fa-check-circle mr-1"></i> Écritures équilibrées');
            $('#btn-submit').prop('disabled', false);
        } else {
            $('#balance-status')
                .attr('class', 'badge badge-soft-danger px-3 py-2 font-weight-bold')
                .html('<i class="fa fa-exclamation-circle mr-1"></i> Écritures déséquilibrées');
            $('#btn-submit').prop('disabled', true);
        }
    }

    calculateTotals();
})();
</script>