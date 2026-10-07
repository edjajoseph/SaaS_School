<form method="POST" action="{{ route('settings.academic.period-types.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code du découpage -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code') }}" 
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
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Intitulé du découpage") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
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
                        <i class="fa fa-align-left text-primary mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" 
                              class="form-control form-control-alternative @error('description') is-invalid @enderror" 
                              rows="2" 
                              placeholder="Brève description optionnelle du type de découpage...">{{ old('description') }}</textarea>
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
                        <i class="fa fa-list-ol text-primary mr-1"></i> {{ __("Éléments inclus (Sous-périodes)") }} <span class="text-danger">*</span>
                    </label>
                    <button type="button" class="btn btn-outline-primary btn-sm btn-round waves-effect" id="add-item-row">
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
                        <tbody id="items-container">
                            @if(old('items'))
                                @foreach(old('items') as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][sequence_order]" class="form-control form-control-alternative" value="{{ $item['sequence_order'] }}" min="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-alternative" value="{{ $item['name'] }}" placeholder="Ex: Semestre 1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][code]" class="form-control form-control-alternative" value="{{ $item['code'] ?? '' }}" placeholder="Ex: S1">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-danger btn-mini remove-row"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <input type="number" name="items[0][sequence_order]" class="form-control form-control-alternative" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][name]" class="form-control form-control-alternative" placeholder="Ex: Semestre 1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][code]" class="form-control form-control-alternative" placeholder="Ex: S1">
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-danger btn-mini remove-row"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale avec séparateur -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> {{ __('Réinitialiser') }}
            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> {{ __('Enregistrer') }}
            </button>
        </div>
    </div>
</form>

<script>
    (function() {
        let itemIndex = {{ old('items') ? count(old('items')) : 1 }};

        document.getElementById('add-item-row').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const rowCount = container.querySelectorAll('tr').length + 1;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="number" name="items[${itemIndex}][sequence_order]" class="form-control form-control-alternative" value="${rowCount}" min="1" required>
                </td>
                <td>
                    <input type="text" name="items[${itemIndex}][name]" class="form-control form-control-alternative" placeholder="Ex: Semestre ${rowCount}" required>
                </td>
                <td>
                    <input type="text" name="items[${itemIndex}][code]" class="form-control form-control-alternative" placeholder="Ex: S${rowCount}">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-mini remove-row"><i class="fa fa-times"></i></button>
                </td>
            `;
            container.appendChild(tr);
            itemIndex++;
        });

        document.getElementById('items-container').addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('#items-container tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Attention', text: 'Vous devez conserver au moins un élément.', confirmButtonColor: '#f8ac59' });
                }
            }
        });
    })();
</script>