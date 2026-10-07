<form method="POST" action="{{  route('academic.school-series.update', $school_series) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Sélection de l'Établissement / École -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-success mr-1"></i> {{ __("Établissement / École") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $school_series->school_id) == $school->id ? 'selected' : '' }}>
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

            <!-- Sélection de la Série -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-bookmark text-success mr-1"></i> {{ __("Série") }} <span class="text-danger">*</span>
                    </label>
                    <select name="serie_id" class="form-control form-control-alternative @error('serie_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez la série") }}</option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" {{ old('serie_id', $school_series->serie_id) == $serie->id ? 'selected' : '' }}>
                                {{ $serie->name ?? $serie->code }} {{ isset($serie->code) && $serie->name ? '('.$serie->code.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('serie_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Statut d'activation -->
            <div class="col-md-12 mt-2">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           class="form-control" 
                           id="is_active_school_serie_edit" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           {{ old('is_active', $school_series->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark mb-0 pl-2" for="is_active_school_serie_edit" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Série active dans cet établissement") }}
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
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>