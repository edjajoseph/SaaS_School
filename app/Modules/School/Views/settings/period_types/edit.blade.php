<form method="POST" action="{{ route('settings.academic.period-types.update', $periodType) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code du découpage -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code', $periodType->code) }}" 
                           placeholder="Ex: SEMESTRE" 
                           required>
                    @error('code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Intitulé du découpage -->
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-success mr-1"></i> {{ __("Intitulé du découpage") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name', $periodType->name) }}" 
                           placeholder="Ex: Semestriel" 
                           required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12 mb-3">
                <div class="form-group mb-0">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-success mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" 
                              class="form-control form-control-alternative @error('description') is-invalid @enderror" 
                              rows="2" 
                              placeholder="Description optionnelle...">{{ old('description', $periodType->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Composition dynamique des sous-périodes -->
            <div class="col-md-12">
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label font-weight-bold text-dark mb-0">
                        <i class="fa fa-list-ol text-success mr-1"></i> {{ __("Éléments inclus (Sous-périodes)") }} <span class="text-danger">*</span>
                    </label>
                    <button type="button" class="btn btn-outline-success btn-sm btn-round waves-effect" id="add-edit-item-row">
                        <i class="fa fa-plus mr-1"></i> {{ __("Ajouter un élément") }}
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 100px;">{{ __("Ordre") }} <span class="text-danger">*</span></th>
                                <th>{{ __("Nom de la période") }} <span class="text-danger">*</span></th>
                                <th>{{ __("Code") }}</th>
                                <th style="width: 50px;" class="text-center"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="edit-items-container">
                            @php
                                $itemsToIterate = old('items', $periodType->items);
                            @endphp

                            @foreach($itemsToIterate as $index => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ is_array($item) ? ($item['id'] ?? '') : $item->id }}">
                                        <input type="number" name="items[{{ $index }}][sequence_order]" class="form-control form-control-alternative" value="{{ is_array($item) ? $item['sequence_order'] : $item->sequence_order }}" min="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-alternative" value="{{ is_array($item) ? $item['name'] : $item->name }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][code]" class="form-control form-control-alternative" value="{{ is_array($item) ? ($item['code'] ?? '') : $item->code }}">
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-danger btn-mini remove-edit-row"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>

<script>
    (function() {
        let editItemIndex = {{ count($itemsToIterate) }};

        document.getElementById('add-edit-item-row').addEventListener('click', function () {
            const container = document.getElementById('edit-items-container');
            const rowCount = container.querySelectorAll('tr').length + 1;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="hidden" name="items[${editItemIndex}][id]" value="">
                    <input type="number" name="items[${editItemIndex}][sequence_order]" class="form-control form-control-alternative" value="${rowCount}" min="1" required>
                </td>
                <td>
                    <input type="text" name="items[${editItemIndex}][name]" class="form-control form-control-alternative" placeholder="Ex: Période ${rowCount}" required>
                </td>
                <td>
                    <input type="text" name="items[${editItemIndex}][code]" class="form-control form-control-alternative" placeholder="Ex: P${rowCount}">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-mini remove-edit-row"><i class="fa fa-times"></i></button>
                </td>
            `;
            container.appendChild(tr);
            editItemIndex++;
        });

        document.getElementById('edit-items-container').addEventListener('click', function (e) {
            if (e.target.closest('.remove-edit-row')) {
                const rows = document.querySelectorAll('#edit-items-container tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Attention', text: 'Vous devez conserver au moins un élément.', confirmButtonColor: '#f8ac59' });
                }
            }
        });
    })();
</script>