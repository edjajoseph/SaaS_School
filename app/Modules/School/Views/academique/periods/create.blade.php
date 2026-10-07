<style>
    /* 1. Masquer totalement le select HTML d'origine sous Select2 */
    select.select2-hidden-accessible {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        border: 0 !important;
        background: transparent !important;
    }

    /* 2. Style propre et ajusté du champ Select2 (aligné avec les champs dates) */
    .modal-body .select2-container {
        width: 100% !important;
        display: block !important;
    }

    .modal-body .select2-container--default .select2-selection--single {
        background-color: #ffffff !important;
        background-image: none !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
        height: 38px !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: none !important;
    }

    .modal-body .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057 !important;
        line-height: 36px !important;
        padding-left: 10px !important;
        padding-right: 25px !important;
        background: transparent !important;
    }

    .modal-body .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 8px !important;
        top: 0 !important;
        background: transparent !important;
    }

    /* 3. Dropdown propre et sans débordement */
    .select2-dropdown {
        z-index: 99999 !important;
        border: 1px solid #ced4da !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-search--dropdown {
        padding: 6px !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 3px !important;
        height: 32px !important;
        padding: 4px 8px !important;
        outline: none !important;
    }

    .select2-results__option {
        padding: 6px 12px !important;
        font-size: 13.5px !important;
        color: #333333 !important;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #04a9f5 !important;
        color: #ffffff !important;
    }
</style>
<form method="POST" action="{{ route('organisation.periods.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        {{-- En-tête : Établissement / Année / Type --}}
        <div class="row">
            @if(isset($isAdmin) && $isAdmin)
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark" for="school_id">
                            <i class="fa fa-university text-success mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                        </label>
                        <select name="school_id" id="school_id" class="custom-select shadow-sm @error('school_id') is-invalid @enderror" required>
                            <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="{{ isset($isAdmin) && $isAdmin ? 'col-md-6' : 'col-md-6' }}">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark" for="academic_year_id">
                        <i class="fa fa-graduation-cap text-success mr-1"></i> {{ __("Année académique") }} <span class="text-danger">*</span>
                    </label>
                    <select name="academic_year_id" id="academic_year_id" class="custom-select shadow-sm @error('academic_year_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'année") }}</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id || ($year->is_current && !old('academic_year_id'))) ? 'selected' : '' }}>
                                {{ $year->name ?? $year->code }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="{{ isset($isAdmin) && $isAdmin ? 'col-md-6' : 'col-md-6' }}">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark" for="global_period_type_id">
                        <i class="fa fa-layer-group text-primary mr-1"></i> {{ __("Type de découpage") }} <span class="text-danger">*</span>
                    </label>
                    <select name="period_type_id" id="global_period_type_id" class="custom-select shadow-sm @error('period_type_id') is-invalid @enderror" required>
                        <option value="">{{ __("-- Sélectionner le type --") }}</option>
                        @foreach($periodTypes as $type)
                            <option value="{{ $type->id }}" {{ old('period_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('period_type_id')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Configuration des périodes --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="fa fa-calendar-alt text-primary mr-1"></i> {{ __('Configuration des périodes') }}
                </h5>
                <small class="text-muted">{{ __('Définissez les dates et cochez la période actuellement en cours.') }}</small>
            </div>
            <button type="button" id="btn-add-period" class="btn btn-sm btn-outline-success btn-round shadow-sm">
                <i class="fa fa-plus-circle mr-1"></i> {{ __('Ajouter une période') }}
            </button>
        </div>

        @if($errors->has('periods'))
            <div class="alert alert-danger py-2 small">{{ $errors->first('periods') }}</div>
        @endif

        <div id="periods-container">
            @php
                $oldPeriods = old('periods', [
                    ['period_type_item_id' => '', 'start_date' => '', 'end_date' => '', 'is_closed' => false]
                ]);
                $selectedCurrentIndex = old('current_period_index', 0);
            @endphp

            @foreach($oldPeriods as $index => $row)
                <div class="card border mb-3 period-item shadow-none" data-index="{{ $index }}">
                    <div class="card-body p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-primary period-number">Période #{{ $loop->iteration }}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-period {{ count($oldPeriods) === 1 ? 'd-none' : '' }}" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label class="form-label font-weight-bold small text-dark">
                                        {{ __("Période / Terme") }} <span class="text-danger">*</span>
                                    </label>
                                    <select name="periods[{{ $index }}][period_type_item_id]" 
                                            class="custom-select custom-select-sm select-period-item @error("periods.$index.period_type_item_id") is-invalid @enderror" 
                                            data-selected="{{ $row['period_type_item_id'] ?? '' }}" 
                                            required>
                                        <option value="">{{ __("-- Choisir la période --") }}</option>
                                    </select>
                                    @error("periods.$index.period_type_item_id")
                                        <span class="invalid-feedback d-block small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label font-weight-bold small text-dark">
                                        {{ __("Date début") }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="periods[{{ $index }}][start_date]" class="form-control form-control-sm @error("periods.$index.start_date") is-invalid @enderror" value="{{ $row['start_date'] ?? '' }}" required>
                                    @error("periods.$index.start_date")
                                        <span class="invalid-feedback d-block small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label font-weight-bold small text-dark">
                                        {{ __("Date fin") }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="periods[{{ $index }}][end_date]" class="form-control form-control-sm @error("periods.$index.end_date") is-invalid @enderror" value="{{ $row['end_date'] ?? '' }}" required>
                                    @error("periods.$index.end_date")
                                        <span class="invalid-feedback d-block small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2 d-flex flex-column justify-content-center pt-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input period-current-radio" type="radio" name="current_period_index" id="is_current_{{ $index }}" value="{{ $index }}" {{ (string)$selectedCurrentIndex === (string)$index ? 'checked' : '' }} style="cursor: pointer;">
                                    <label class="form-check-label font-weight-bold text-success small" for="is_current_{{ $index }}" style="cursor: pointer; margin-left: 4px;">
                                        {{ __('En cours') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="periods[{{ $index }}][is_closed]" id="is_closed_{{ $index }}" value="1" {{ !empty($row['is_closed']) ? 'checked' : '' }} style="cursor: pointer;">
                                    <label class="form-check-label font-weight-bold text-danger small" for="is_closed_{{ $index }}" style="cursor: pointer; margin-left: 4px;">
                                        {{ __('Clôturée') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TEMPLATE HTML --}}
    <template id="period-row-template">
        <div class="card border mb-3 period-item shadow-none" data-index="__INDEX__">
            <div class="card-body p-3 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge badge-primary period-number">Période</span>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-period" title="Supprimer">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label class="form-label font-weight-bold small text-dark">
                                {{ __("Période / Terme") }} <span class="text-danger">*</span>
                            </label>
                            <select name="periods[__INDEX__][period_type_item_id]" class="custom-select custom-select-sm select-period-item" required>
                                <option value="">{{ __("-- Choisir la période --") }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="form-label font-weight-bold small text-dark">
                                {{ __("Date début") }} <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="periods[__INDEX__][start_date]" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="form-label font-weight-bold small text-dark">
                                {{ __("Date fin") }} <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="periods[__INDEX__][end_date]" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column justify-content-center pt-2">
                        <div class="form-check mb-2">
                            <input class="form-check-input period-current-radio" type="radio" name="current_period_index" id="is_current___INDEX__" value="__INDEX__" style="cursor: pointer;">
                            <label class="form-check-label font-weight-bold text-success small" for="is_current___INDEX__" style="cursor: pointer; margin-left: 4px;">
                                {{ __('En cours') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="periods[__INDEX__][is_closed]" id="is_closed___INDEX__" value="1" style="cursor: pointer;">
                            <label class="form-check-label font-weight-bold text-danger small" for="is_closed___INDEX__" style="cursor: pointer; margin-left: 4px;">
                                {{ __('Clôturée') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('organisation.periods.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-save mr-1"></i> {{ __('Enregistrer toutes les périodes') }}
        </button>
    </div>
</form>
<script>
    $(document).ready(function () {
        const periodTypeItems = @json($periodTypeItems);
        const $container = $('#periods-container');
        const $globalType = $('#global_period_type_id');
        const templateHtml = $('#period-row-template').html();
        let nextIndex = $container.find('.period-item').length;
        let isUpdating = false;

        // Récupère la modale parente active
        function getParentModal($el) {
            const $modal = $el.closest('.modal');
            return $modal.length ? $modal : $('body');
        }

        // 1. Initialiser les sélecteurs statiques
        $('#school_id, #academic_year_id, #global_period_type_id').each(function() {
            $(this).select2({
                dropdownParent: getParentModal($(this)),
                width: '100%'
            });
        });

        // 2. Synchroniser les sous-périodes dynamiques
        function refreshPeriodSelects(resetValues = false) {
            if (isUpdating) return;
            isUpdating = true;

            const selectedTypeId = $globalType.val();
            const chosenValues = [];

            $('.select-period-item').each(function() {
                const val = $(this).val();
                if (val) chosenValues.push(String(val));
            });

            const availableItems = selectedTypeId 
                ? periodTypeItems.filter(item => String(item.period_type_id) === String(selectedTypeId))
                : [];

            $('.select-period-item').each(function() {
                const $select = $(this);
                const initialVal = $select.attr('data-selected');
                let currentValue = resetValues ? '' : ($select.val() || initialVal || '');

                if ($select.data('select2')) {
                    $select.select2('destroy');
                }

                $select.empty();

                if (!selectedTypeId) {
                    $select.append(new Option('-- Sélectionnez le type --', ''));
                } else if (availableItems.length === 0) {
                    $select.append(new Option('-- Aucun terme disponible --', ''));
                } else {
                    $select.append(new Option('-- Choisir la période --', ''));

                    availableItems.forEach(item => {
                        const itemId = String(item.id);
                        if (!chosenValues.includes(itemId) || itemId === String(currentValue)) {
                            const isSelected = (itemId === String(currentValue));
                            $select.append(new Option(item.name, item.id, isSelected, isSelected));
                        }
                    });
                }

                $select.removeAttr('data-selected');

                $select.select2({
                    dropdownParent: getParentModal($select),
                    width: '100%'
                });
            });

            isUpdating = false;
        }

        function updateRowUI() {
            const $items = $container.find('.period-item');
            $items.each(function(i) {
                $(this).find('.period-number').text(`Période #${i + 1}`);
                $(this).find('.btn-remove-period').toggleClass('d-none', $items.length === 1);
            });
        }

        // Événements
        $globalType.on('select2:select change', function () {
            refreshPeriodSelects(true);
        });

        $container.on('change', '.select-period-item', function () {
            if (!isUpdating) {
                refreshPeriodSelects(false);
            }
        });

        $('#btn-add-period').on('click', function (e) {
            e.preventDefault();
            const currentIndex = nextIndex++;
            const rowContent = templateHtml.replace(/__INDEX__/g, currentIndex);
            
            $container.append(rowContent);
            updateRowUI();
            refreshPeriodSelects(false);
        });

        $container.on('click', '.btn-remove-period', function (e) {
            e.preventDefault();
            const $item = $(this).closest('.period-item');
            const wasChecked = $item.find('.period-current-radio').is(':checked');

            if ($container.find('.period-item').length > 1) {
                $item.remove();
                updateRowUI();
                
                if (wasChecked) {
                    $container.find('.period-current-radio').first().prop('checked', true);
                }
                
                refreshPeriodSelects(false);
            }
        });

        // Lancer au chargement
        refreshPeriodSelects(false);
        updateRowUI();
    });
</script>
