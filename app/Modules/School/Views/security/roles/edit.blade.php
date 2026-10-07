<form method="POST" action="{{ route('access.roles.update', $role->id) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Informations générales -->
            <div class="col-md-12">
                <h6 class="font-weight-bold text-success mb-3">
                    <i class="fa fa-info-circle mr-1"></i> {{ __("Informations du Rôle") }}
                </h6>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tag text-success mr-1"></i> {{ __("Nom technique (Code)") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-font text-success mr-1"></i> {{ __("Libellé d'affichage") }}
                    </label>
                    <input type="text" name="display_name" class="form-control form-control-alternative @error('display_name') is-invalid @enderror" value="{{ old('display_name', $role->display_name) }}">
                    @error('display_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group mb-4">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-success mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" class="form-control form-control-alternative @error('description') is-invalid @enderror" rows="2">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12"><hr class="my-2"></div>

            <!-- Permissions -->
            <div class="col-md-12 mt-2">
                <h6 class="font-weight-bold text-success mb-3">
                    <i class="fa fa-key mr-1"></i> {{ __("Permissions par Modules & Fonctionnalités") }}
                </h6>
                
                @php
                    $rolePermissions = old('permissions', $role->permissions->pluck('id')->toArray());
                @endphp

                @foreach($groupedPermissions as $moduleName => $features)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-header bg-light py-2 border-bottom">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fa fa-folder-open text-warning mr-1"></i> MODULE : {{ strtoupper($moduleName) }}
                            </h6>
                        </div>
                        
                        <div class="card-body py-3">
                            <div class="row">
                                @foreach($features as $featureName => $permissions)
                                    <div class="col-md-6 mb-3">
                                        <div class="border p-2 rounded">
                                            <h6 class="text-secondary border-bottom pb-1 mb-2 font-weight-bold small">
                                                <i class="fa fa-cogs"></i> {{ $featureName }}
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check mr-3 mb-1">
                                                        <input class="form-check-input" 
                                                            type="checkbox" 
                                                            name="permissions[]" 
                                                            value="{{ $permission->id }}" 
                                                            id="edit_perm_{{ $permission->id }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label small text-dark font-weight-bold" for="edit_perm_{{ $permission->id }}">
                                                            {{ $permission->display_name ?? $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pied de page modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Annuler') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>