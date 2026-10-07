<form method="POST" action="{{  route('academic.school-series.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Sélection de l'Établissement / École -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-primary mr-1"></i> {{ __("Établissement / École") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $currentSchoolId ?? '') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Sélection Multiple des Séries avec Checkboxes -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label font-weight-bold text-dark mb-0">
                            <i class="fa fa-bookmark text-primary mr-1"></i> {{ __("Sélectionnez les Séries") }} <span class="text-danger">*</span>
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="selectAllSeries">
                            {{ __("Tout cocher / Décocher") }}
                        </button>
                    </div>

                    <div class="border rounded p-3 bg-light" style="max-height: 220px; overflow-y: auto;">
                        <div class="row">
                            @foreach($series as $serie)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input serie-checkbox" 
                                               type="checkbox" 
                                               name="serie_ids[]" 
                                               value="{{ $serie->id }}" 
                                               id="serie_{{ $serie->id }}"
                                               style="width: 16px; height: 16px; cursor: pointer;">
                                        <label class="form-check-label font-weight-bold text-dark mb-0 pl-2" for="serie_{{ $serie->id }}" style="cursor: pointer;">
                                            {{ $serie->name ?? $serie->code }} {{ isset($serie->code) && $serie->name ? '('.$serie->code.')' : '' }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('serie_ids')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Statut d'activation globale -->
            <div class="col-md-12 mt-2">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           class="form-control" 
                           id="is_active_school_serie_create" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark mb-0 pl-2" for="is_active_school_serie_create" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Activer immédiatement les séries sélectionnées") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('academic.school-series.index') }}" class="btn btn-secondary btn-round waves-effect" >
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> {{ __('Réinitialiser') }}
            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> {{ __('Enregistrer les associations') }}
            </button>
        </div>
    </div>
</form>

<script>
    document.getElementById('selectAllSeries')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.serie-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    });
</script>