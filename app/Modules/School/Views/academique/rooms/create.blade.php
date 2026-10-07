<form method="POST" action="{{ route('academic.rooms.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            @if(isset($isAdmin) && $isAdmin)
                <!-- Sélection de l'Établissement (Mode Admin) -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">
                            <i class="fa fa-building text-primary mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                        </label>
                        <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un établissement --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
            @else
                <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
            @endif

            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code / Sigle") }}
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ex: SALLE-101">
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-primary mr-1"></i> {{ __("Nom de la salle") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Salle Informatique A" required>
                    @error('name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Bâtiment -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building-o text-primary mr-1"></i> {{ __("Bâtiment") }}
                    </label>
                    <input type="text" name="building" class="form-control form-control-alternative @error('building') is-invalid @enderror" value="{{ old('building') }}" placeholder="Ex: Pavillon B">
                    @error('building')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Étage -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-level-up text-primary mr-1"></i> {{ __("Étage") }}
                    </label>
                    <input type="number" name="floor" class="form-control form-control-alternative @error('floor') is-invalid @enderror" value="{{ old('floor', 0) }}" placeholder="Ex: 1">
                    @error('floor')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Capacité -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> {{ __("Capacité (Places)") }}
                    </label>
                    <input type="number" name="capacity" class="form-control form-control-alternative @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" placeholder="Ex: 40">
                    @error('capacity')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="checkbox-fade fade-in-primary">
                    <label for="is_active_create" class="font-weight-bold text-dark">
                        <input type="checkbox" name="is_active" id="is_active_create" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span>{{ __("Rendre cette salle immédiatement active") }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('academic.rooms..index') }}" class="btn btn-secondary btn-round waves-effect">
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