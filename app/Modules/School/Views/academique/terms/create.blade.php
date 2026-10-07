<form method="POST" action="{{ route('academic.terms.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Année académique -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar-o text-primary mr-1"></i> {{ __("Année Académique") }} <span class="text-danger">*</span>
                    </label>
                    <select name="academic_year_id" class="form-control form-control-alternative @error('academic_year_id') is-invalid @enderror" required>
                        <option value="">-- {{ __("Sélectionner l'année") }} --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Nom de la période -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-bookmark text-primary mr-1"></i> {{ __("Libellé / Nom") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Ex: Semestre 1 / Trimestre 1" 
                           required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Code -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code") }}
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code') }}" 
                           placeholder="Ex: S1, T1">
                    @error('code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Ordre de séquence -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-sort-numeric-asc text-primary mr-1"></i> {{ __("Ordre de séquence") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="sequence_order" 
                           class="form-control form-control-alternative @error('sequence_order') is-invalid @enderror" 
                           value="{{ old('sequence_order', 1) }}" 
                           min="1" 
                           required>
                    @error('sequence_order')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Date de début -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar-plus-o text-primary mr-1"></i> {{ __("Date de début") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="start_date" 
                           class="form-control form-control-alternative @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date') }}" 
                           required>
                    @error('start_date')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Date de fin -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar-minus-o text-primary mr-1"></i> {{ __("Date de fin") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="end_date" 
                           class="form-control form-control-alternative @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date') }}" 
                           required>
                    @error('end_date')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Checkboxes Statuts -->
            <div class="col-md-6 mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_current" value="1" id="is_current_create" {{ old('is_current') ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark ml-2" for="is_current_create">
                        <i class="fa fa-star text-warning mr-1"></i> {{ __("Définir comme période courante") }}
                    </label>
                </div>
            </div>

            <div class="col-md-6 mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_closed" value="1" id="is_closed_create" {{ old('is_closed') ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark ml-2" for="is_closed_create">
                        <i class="fa fa-lock text-danger mr-1"></i> {{ __("Clôturer la période") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('academic.terms.index') }}" class="btn btn-secondary btn-round waves-effect" >
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
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