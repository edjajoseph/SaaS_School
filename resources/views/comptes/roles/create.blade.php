<div class="row">
    <div class="col-sm-12 b-r">
        <h6 class="m-t-none m-b">FORMULAIRE DE CRÉATION DES RÔLES</h6>
        <hr>

        {{-- 1. Gestion des alertes globales --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <form role="form" method="POST" action="{{ route('roles.store') }}" autocomplete="off">
            @csrf

            <fieldset>
                {{-- Champ Nom du rôle --}}
                <div class="form-group mb-3">
                    <label class="form-label"><b>{{ __("Rôle") }} <span class="text-danger">*</span></b></label> 
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="ex: admin, manager..." required>
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Champ Libellé --}}
                <div class="form-group mb-3">
                    <label class="form-label"><b>{{ __("Libellé") }}</b></label> 
                    <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name') }}" placeholder="ex: Administrateur système">
                    @error('display_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Champ Description --}}
                <div class="form-group mb-3">
                    <label class="form-label"><b>{{ __("Description") }}</b></label> 
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description du rôle">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <hr>

                {{-- 2. Section des Permissions sous forme de cases à cocher --}}
                <div class="form-group mb-4">
					<label class="form-label d-block"><b>{{ __("Permissions par Modules & Fonctionnalités") }}</b></label>

					@php
						$rolePermissions = isset($role) ? old('permissions', $role->permissions->pluck('id')->toArray()) : old('permissions', []);
					@endphp

					@foreach($groupedPermissions as $moduleName => $features)
						<div class="card mb-3 border">
							{{-- Entête du Module --}}
							<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
								<h6 class="mb-0 font-weight-bold"><i class="fa fa-folder"></i> MODULE : {{ strtoupper($moduleName) }}</h6>
							</div>
							
							<div class="card-body">
								<div class="row">
									@foreach($features as $featureName => $permissions)
										<div class="col-md-6 mb-3">
											<div class="border p-3 rounded bg-light">
												{{-- Titre de la fonctionnalité --}}
												<h6 class="text-secondary border-bottom pb-2 mb-2 font-weight-bold">
													<i class="fa fa-cogs"></i> {{ $featureName }}
												</h6>

												{{-- Liste des actions (create, update, show, delete) --}}
												<div class="d-flex flex-wrap gap-3">
													@foreach($permissions as $permission)
														<div class="form-check mr-3 mb-2">
															<input class="form-check-input" 
																type="checkbox" 
																name="permissions[]" 
																value="{{ $permission->id }}" 
																id="perm_{{ $permission->id }}"
																{{ is_array($rolePermissions) && in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
															<label class="form-check-label" for="perm_{{ $permission->id }}">
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

                {{-- Bouton de soumission --}}
                <div>
                    <button class="btn btn-primary btn-rounded" type="submit">
                        <strong><i class="fa fa-save"></i>&nbsp; {{ __('Enregistrer') }}</strong>
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>