<form method="POST" action="{{ route('settings.academic.subjects.update', $subject) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code / Sigle") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $subject->code) }}" required>
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-success mr-1"></i> {{ __("Nom de la matière") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $subject->name) }}" required>
                    @error('name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-success mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" rows="3" class="form-control form-control-alternative @error('description') is-invalid @enderror">{{ old('description', $subject->description) }}</textarea>
                    @error('description')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active_edit" value="1" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="is_active_edit">
                        {{ __("Matière active dans le référentiel") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>