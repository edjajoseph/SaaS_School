<form method="POST" action="{{ route('settings.academic.series.update', $serie) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code de la série -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code', $serie->code) }}" 
                           placeholder="Ex: C, D, A1, STI..." 
                           required>
                    @error('code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Nom de la série -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-bookmark text-success mr-1"></i> {{ __("Nom de la série") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name', $serie->name) }}" 
                           placeholder="Ex: Série Mathématiques & Sciences Physiques" 
                           required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-success mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" 
                              rows="3" 
                              class="form-control form-control-alternative @error('description') is-invalid @enderror" 
                              placeholder="Description optionnelle de la série...">{{ old('description', $serie->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Statut d'activation -->
            <div class="col-md-12 mt-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           class="custom-control-input" 
                           id="is_active_edit" 
                           {{ old('is_active', $serie->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="is_active_edit">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Série active") }}
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