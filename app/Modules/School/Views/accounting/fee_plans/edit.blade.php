<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<form id="editFeePlanForm" action="{{ route('school.fee-plans.update', $feePlan->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- En-tête du Plan Tarifaire -->
    <div class="row">
        <div class="col-md-12 form-group">
            <label for="school_id">Établissement <span class="text-danger">*</span></label>
            <select name="school_id" id="school_id" class="form-control" required>
                <option value="">-- Sélectionner l'établissement --</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ old('school_id', $feePlan->school_id) == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label class="font-weight-bold">Intitulé du plan tarifaire <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="ex: Scolarité L3 Informatique 2026-2027" value="{{ old('name', $feePlan->name) }}" required>
        </div>
        
        <div class="col-md-6 form-group">
            <label class="font-weight-bold">Année Académique <span class="text-danger">*</span></label>
            <select name="academic_year_id" class="form-control" required>
                <option value="">-- Sélectionner l'année --</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ old('academic_year_id', $feePlan->academic_year_id) == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 form-group">
            <label class="font-weight-bold">Niveau d'étude (Optionnel)</label>
            <select name="level_id" class="form-control">
                <option value="">Tous les niveaux</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $feePlan->level_id) == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label class="font-weight-bold">Classe spécifique (Optionnel)</label>
            <select name="school_class_id" class="form-control">
                <option value="">Toutes les classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ old('school_class_id', $feePlan->school_class_id) == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label class="font-weight-bold">Montant Total Scolarité (FCFA) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" id="total_amount" name="total_amount" class="form-control" placeholder="ex: 500000" value="{{ old('total_amount', $feePlan->total_amount) }}" required>
        </div>
    </div>

    <hr class="hr-gradient">

    <!-- Section Échéancier (Tranches) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-primary font-weight-bold">
            <i class="feather icon-list mr-2"></i>DÉCOUPAGE DE L'ÉCHÉANCIER (TRANCHES)
        </h5>
        <button type="button" class="btn btn-success btn-sm btn-round waves-effect shadow-sm" id="edit-add-item-btn">
            <i class="fa fa-plus mr-1"></i> Ajouter une tranche
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="edit-items-table">
            <thead class="thead-light">
                <tr>
                    <th>Libellé de la tranche <span class="text-danger">*</span></th>
                    <th width="25%">Montant (FCFA) <span class="text-danger">*</span></th>
                    <th width="25%">Date Limite <span class="text-danger">*</span></th>
                    <th width="12%" class="text-center">Bloquant ?</th>
                    <th width="5%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="edit-items-container">
                @foreach($feePlan->items as $index => $item)
                    <tr>
                        <td>
                            <input type="text" name="items[{{ $index }}][label]" class="form-control" value="{{ $item->label }}" placeholder="ex: Tranche {{ $index + 1 }}" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[{{ $index }}][amount]" class="form-control item-amount" value="{{ $item->amount }}" placeholder="0" required>
                        </td>
                        <td>
                            <input type="date" name="items[{{ $index }}][due_date]" class="form-control" value="{{ \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') }}" required>
                        </td>
                        <td class="text-center align-middle">
                            <div class="border-checkbox-section">
                                <div class="border-checkbox-group border-checkbox-group-primary mb-0">
                                    <input class="border-checkbox" type="checkbox" name="items[{{ $index }}][is_blocking]" id="edit_checkbox{{ $index }}" value="1" {{ $item->is_blocking ? 'checked' : '' }}>
                                    <label class="border-checkbox-label mb-0" for="edit_checkbox{{ $index }}"></label>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-danger btn-mini remove-item" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal-footer px-0 pb-0 pt-3">
        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary waves-effect shadow-sm">
            <i class="fa fa-save mr-1"></i> Mettre à jour le Plan
        </button>
    </div>
</form>

<script>
(function() {
    let itemIndex = {{ count($feePlan->items) }};
    const container = document.getElementById('edit-items-container');
    const addBtn = document.getElementById('edit-add-item-btn');

    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="text" name="items[${itemIndex}][label]" class="form-control" placeholder="ex: Tranche ${itemIndex + 1}" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control item-amount" placeholder="0" required>
                </td>
                <td>
                    <input type="date" name="items[${itemIndex}][due_date]" class="form-control" required>
                </td>
                <td class="text-center align-middle">
                    <div class="border-checkbox-section">
                        <div class="border-checkbox-group border-checkbox-group-primary mb-0">
                            <input class="border-checkbox" type="checkbox" name="items[${itemIndex}][is_blocking]" id="edit_checkbox${itemIndex}" value="1" checked>
                            <label class="border-checkbox-label mb-0" for="edit_checkbox${itemIndex}"></label>
                        </div>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-mini remove-item" title="Supprimer">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            container.appendChild(row);
            itemIndex++;
        });
    }

    if (container) {
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item')) {
                const rows = container.querySelectorAll('tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention !',
                        text: 'Le plan tarifaire doit au moins comporter une tranche.',
                        confirmButtonColor: '#f8ac59'
                    });
                }
            }
        });
    }
})();
</script>