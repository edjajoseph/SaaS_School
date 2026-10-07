<form method="POST" action="{{ route('organisation.academic-years.update', $academicYear) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Nom / Libellé de l'année -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar text-success mr-1"></i> {{ __("Libellé / Nom") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name', $academicYear->name) }}" 
                           placeholder="Ex: 2025-2026" 
                           required>
                    @error('name')
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
                        <i class="fa fa-calendar-plus-o text-success mr-1"></i> {{ __("Date de début") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="start_date" 
                           class="form-control form-control-alternative @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', optional($academicYear->start_date)->format('Y-m-d') ?? $academicYear->start_date) }}" 
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
                        <i class="fa fa-calendar-minus-o text-success mr-1"></i> {{ __("Date de fin") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="end_date" 
                           class="form-control form-control-alternative @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date', optional($academicYear->end_date)->format('Y-m-d') ?? $academicYear->end_date) }}" 
                           required>
                    @error('end_date')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Option Année en cours -->
            <div class="col-md-12 mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_current" value="1" id="is_current_edit" {{ old('is_current', $academicYear->is_current) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark ml-2" for="is_current_edit">
                        <i class="fa fa-star text-warning mr-1"></i> {{ __("Définir comme année académique courante") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('organisation.academic-years.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>