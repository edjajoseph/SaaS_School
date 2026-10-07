<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 font-weight-bold text-dark">
                <i class="fa fa-edit text-success mr-2"></i>Modifier la Pièce Comptable : <?php echo e($entry->entry_number); ?>

            </h4>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('school.accounting.entries.update', $entry->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-row mb-3">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Exercice Comptable</label>
                        <select name="fiscal_year_id" class="form-control" required>
                            <?php $__currentLoopData = $fiscalYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($fy->id); ?>" <?php echo e($entry->fiscal_year_id == $fy->id ? 'selected' : ''); ?>><?php echo e($fy->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Journal</label>
                        <select name="journal_id" class="form-control" required>
                            <?php $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j->id); ?>" <?php echo e($entry->journal_id == $j->id ? 'selected' : ''); ?>><?php echo e($j->code); ?> - <?php echo e($j->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">N° Pièce / Référence</label>
                        <input type="text" name="entry_number" class="form-control" value="<?php echo e(old('entry_number', $entry->entry_number)); ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Date de Pièce</label>
                        <input type="date" name="entry_date" class="form-control" value="<?php echo e(old('entry_date', $entry->entry_date->format('Y-m-d'))); ?>" required>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Libellé de l'Opération</label>
                    <input type="text" name="label" class="form-control" value="<?php echo e(old('label', $entry->label)); ?>" required>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold text-dark mb-0">Lignes d'Écritures</h5>
                    <span id="balance-status" class="badge badge-soft-success px-3 py-2 font-weight-bold">
                        <i class="fa fa-check-circle mr-1"></i> Écritures équilibrées
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
                        <?php $__currentLoopData = $entry->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="item-row">
                            <td>
                                <select name="items[<?php echo e($index); ?>][chart_of_account_id]" class="form-control" required>
                                    <option value="">-- Choisir un compte --</option>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($acc->id); ?>" <?php echo e($item->chart_of_account_id == $acc->id ? 'selected' : ''); ?>>
                                            <?php echo e($acc->code); ?> - <?php echo e($acc->label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td><input type="text" name="items[<?php echo e($index); ?>][label]" class="form-control" value="<?php echo e($item->label); ?>"></td>
                            <td><input type="number" step="0.01" name="items[<?php echo e($index); ?>][debit]" class="form-control text-right calc-debit" value="<?php echo e($item->debit); ?>"></td>
                            <td><input type="number" step="0.01" name="items[<?php echo e($index); ?>][credit]" class="form-control text-right calc-credit" value="<?php echo e($item->credit); ?>"></td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Totaux & Équilibre :</td>
                            <td class="text-right font-weight-bold text-success" id="total-debit-display">0 FCFA</td>
                            <td class="text-right font-weight-bold text-danger" id="total-credit-display">0 FCFA</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-row">
                    <i class="fa fa-plus mr-1"></i> Ajouter une ligne
                </button>

                <div class="text-right">
                    <a href="<?php echo e(route('school.accounting.entries.index')); ?>" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4" id="btn-submit"><i class="fa fa-save mr-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var rowIndex = <?php echo e($entry->items->count()); ?>;
    var accounts = <?php echo json_encode($accounts, 15, 512) ?>;

    var optionsHtml = '<option value="">-- Choisir un compte --</option>';
    accounts.forEach(function(acc) {
        optionsHtml += '<option value="' + acc.id + '">' + acc.code + ' - ' + acc.label + '</option>';
    });

    $(document).off('click', '#add-row').on('click', '#add-row', function(e) {
        e.preventDefault();
        var row = `
            <tr class="item-row">
                <td><select name="items[${rowIndex}][chart_of_account_id]" class="form-control" required>${optionsHtml}</select></td>
                <td><input type="text" name="items[${rowIndex}][label]" class="form-control" placeholder="Optionnel"></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][debit]" class="form-control text-right calc-debit" value="0.00"></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][credit]" class="form-control text-right calc-credit" value="0.00"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-times"></i></button></td>
            </tr>`;
        $('#items-body').append(row);
        rowIndex++;
        calculateTotals();
    });

    $(document).off('click', '.remove-row').on('click', '.remove-row', function(e) {
        e.preventDefault();
        if ($('#items-body tr').length > 2) {
            $(this).closest('tr').remove();
            calculateTotals();
        } else {
            alert('Une pièce comptable doit comporter au moins deux lignes.');
        }
    });

    $(document).off('input', '.calc-debit, .calc-credit').on('input', '.calc-debit, .calc-credit', function() {
        var $tr = $(this).closest('tr');
        if ($(this).hasClass('calc-debit') && parseFloat($(this).val()) > 0) {
            $tr.find('.calc-credit').val('0.00');
        } else if ($(this).hasClass('calc-credit') && parseFloat($(this).val()) > 0) {
            $tr.find('.calc-debit').val('0.00');
        }
        calculateTotals();
    });

    function calculateTotals() {
        var totalDebit = 0, totalCredit = 0;
        $('.calc-debit').each(function() { totalDebit += parseFloat($(this).val()) || 0; });
        $('.calc-credit').each(function() { totalCredit += parseFloat($(this).val()) || 0; });

        $('#total-debit-display').text(Math.round(totalDebit).toLocaleString('fr-FR') + ' FCFA');
        $('#total-credit-display').text(Math.round(totalCredit).toLocaleString('fr-FR') + ' FCFA');

        var isBalanced = totalDebit > 0 && Math.abs(totalDebit - totalCredit) < 0.01;
        $('#btn-submit').prop('disabled', !isBalanced);
        $('#balance-status')
            .attr('class', 'badge px-3 py-2 font-weight-bold ' + (isBalanced ? 'badge-soft-success' : 'badge-soft-danger'))
            .html(isBalanced ? '<i class="fa fa-check-circle mr-1"></i> Écritures équilibrées' : '<i class="fa fa-exclamation-circle mr-1"></i> Écritures déséquilibrées');
    }

    calculateTotals();
})();
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules\School/Views/accounting/entries/edit.blade.php ENDPATH**/ ?>