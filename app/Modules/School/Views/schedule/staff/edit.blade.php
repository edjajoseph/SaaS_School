<form method="POST" action="{{ route('schedule.staff.update', $staff) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Section 1 : État Civil / Personne -->
            <div class="col-md-12">
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-user text-success mr-1"></i> {{ __("Identité & État Civil") }}
                </h6>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        {{ __("Nom") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nom" class="form-control form-control-alternative @error('nom') is-invalid @enderror" value="{{ old('nom', $staff->personne->nom) }}" placeholder="{{ __("Ex: KOUASSI") }}" required>
                    @error('nom') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        {{ __("Prénoms") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="prenoms" class="form-control form-control-alternative @error('prenoms') is-invalid @enderror" value="{{ old('prenoms', $staff->personne->prenoms) }}" placeholder="{{ __("Ex: Jean-Marc") }}" required>
                    @error('prenoms') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        {{ __("Sexe") }} <span class="text-danger">*</span>
                    </label>
                    <select name="sexe" class="select2-modal @error('sexe') is-invalid @enderror" data-placeholder="{{ __("Sélectionner le sexe") }}" required>
                        <option value=""></option>
                        <option value="M" {{ old('sexe', $staff->personne->sexe) == 'M' ? 'selected' : '' }}>{{ __("Masculin") }}</option>
                        <option value="F" {{ old('sexe', $staff->personne->sexe) == 'F' ? 'selected' : '' }}>{{ __("Féminin") }}</option>
                    </select>
                    @error('sexe') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Civilité") }}</label>
                    <select name="civility" class="form-control form-control-alternative select2-modal" data-placeholder="{{ __("Sélectionner une civilité") }}" required>
                        <option value=""></option>
                        <option value="M" {{ old('civility', $staff->personne->civility) == 'M' ? 'selected' : '' }}>{{ __("Monsieur") }}</option>
                        <option value="Mme" {{ old('civility', $staff->personne->civility) == 'Mme' ? 'selected' : '' }}>{{ __("Madame") }}</option>
                        <option value="Mlle" {{ old('civility', $staff->personne->civility) == 'Mlle' ? 'selected' : '' }}>{{ __("Mademoiselle") }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Situation matrimoniale") }}</label>
                    <select name="sit_mat" class="form-control form-control-alternative select2-modal" data-placeholder="{{ __("Sélectionner une situation matrimoniale") }}" required>
                        <option value=""></option>
                        <option value="Celibataire" {{ old('sit_mat', $staff->personne->sit_mat) == 'Celibataire' ? 'selected' : '' }}>{{ __("Célibataire") }}</option>
                        <option value="Marie" {{ old('sit_mat', $staff->personne->sit_mat) == 'Marie' ? 'selected' : '' }}>{{ __("Marié(e)") }}</option>
                        <option value="Divorce" {{ old('sit_mat', $staff->personne->sit_mat) == 'Divorce' ? 'selected' : '' }}>{{ __("Divorcé(e)") }}</option>
                        <option value="Veuf" {{ old('sit_mat', $staff->personne->sit_mat) == 'Veuf' ? 'selected' : '' }}>{{ __("Veuf(ve)") }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Date de naissance") }}</label>
                    <input type="date" name="birth_date" class="form-control form-control-alternative @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($staff->personne->birth_date)->format('Y-m-d')) }}">
                    @error('birth_date') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Lieu de naissance") }}</label>
                    <input type="text" name="birth_place" class="form-control form-control-alternative @error('birth_place') is-invalid @enderror" value="{{ old('birth_place', $staff->personne->birth_place) }}" placeholder="{{ __("Ex: Abidjan") }}">
                    @error('birth_place') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Nationalité / Pays") }}</label>
                    <select name="country_id" class="select2-modal @error('country_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un pays") }}">
                        <option value=""></option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $staff->personne->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }} ({{ $country->nationality }})
                            </option>
                        @endforeach
                    </select>
                    @error('country_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Section Photo de Profil avec Prévisualisation -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Photo de profil") }}</label>
                    
                    <div class="d-flex align-items-center">
                        <div class="mr-3 position-relative" style="width: 75px; height: 75px;">
                            {{-- Image déjà existante ou prévisualisée --}}
                            <img id="avatar-preview-edit" 
     src="{{ $staff->personne->photo ? route('schedule.staff-file.display', ['path' => $staff->personne->photo]) : '#' }}" 
     alt="Aperçu photo" 
     class="rounded-circle border shadow-sm" 
     style="width: 75px; height: 75px; object-fit: cover; {{ $staff->personne->photo ? 'display: block;' : 'display: none;' }}">
                                 
                            {{-- Placeholder par défaut si aucune photo --}}
                            <div id="avatar-placeholder-edit" 
                                 class="rounded-circle bg-light d-flex align-items-center justify-content-center border" 
                                 style="width: 75px; height: 75px; {{ $staff->personne->photo ? 'display: none !important;' : 'display: flex !important;' }}">
                                <i class="fa fa-user fa-2x text-secondary"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <input type="file" 
                                   name="photo" 
                                   id="photo-input-edit" 
                                   class="form-control form-control-alternative @error('photo') is-invalid @enderror" 
                                   accept="image/png, image/jpeg, image/jpg"
                                   onchange="previewImageEdit(this)">
                            <small class="text-muted d-block mt-1">Formats acceptés : PNG, JPG, JPEG (Max : 2Mo)</small>
                            @error('photo') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Contact -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-envelope text-success mr-1"></i> {{ __("Coordonnées de Contact") }}
                </h6>
            </div>

            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Téléphone") }}</label>
                    <input type="text" name="telephone" class="form-control form-control-alternative @error('telephone') is-invalid @enderror" value="{{ old('telephone', $staff->personne->telephone) }}" placeholder="{{ __("Ex: +225 0700000000") }}">
                    @error('telephone') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Email") }}</label>
                    <input type="email" name="email" class="form-control form-control-alternative @error('email') is-invalid @enderror" value="{{ old('email', $staff->personne->email) }}" placeholder="{{ __("Ex: exemple@domaine.com") }}">
                    @error('email') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Section 2 : Profile Staff & Références -->
            <div class="col-md-12">
                <hr>
                <h6 class="text-success font-weight-bold mb-3">
                    <i class="fa fa-id-badge text-success mr-1"></i> {{ __("Fiche Membre & Profil") }}
                </h6>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        {{ __("Matricule") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="staff_code" class="form-control form-control-alternative @error('staff_code') is-invalid @enderror" value="{{ old('staff_code', $staff->staff_code) }}" placeholder="{{ __("Ex: ENS-001") }}" required>
                    @error('staff_code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Spécialité") }}</label>
                    <select name="speciality_id" class="select2-modal @error('speciality_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner une spécialité") }}">
                        <option value=""></option>
                        @foreach($specialities as $speciality)
                            <option value="{{ $speciality->id }}" {{ old('speciality_id', $staff->speciality_id) == $speciality->id ? 'selected' : '' }}>
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('speciality_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Diplôme") }}</label>
                    <select name="degree_id" class="select2-modal @error('degree_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un diplôme") }}">
                        <option value=""></option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ old('degree_id', $staff->degree_id) == $degree->id ? 'selected' : '' }}>
                                {{ $degree->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('degree_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Section 3 : Mise à jour du Contrat Actif -->
            @php
                $activeContract = $staff->contracts()->where('status', 'active')->latest()->first();
            @endphp

            @if($activeContract)
                <div class="col-md-12">
                    <hr>
                    <h6 class="text-success font-weight-bold mb-3">
                        <i class="fa fa-file-text text-success mr-1"></i> {{ __("Contrat Actif en cours") }}
                    </h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Établissement du contrat") }} <span class="text-danger">*</span></label>
                        <select name="contract_school_id" class="select2-modal @error('contract_school_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un établissement") }}" required>
                            <option value=""></option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('contract_school_id', $activeContract->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_school_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Rôle métier") }} <span class="text-danger">*</span></label>
                        <select name="staff_role_id" class="select2-modal @error('staff_role_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un rôle") }}" required>
                            <option value=""></option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('staff_role_id', $activeContract->staff_role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('staff_role_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Intitulé du poste") }} <span class="text-danger">*</span></label>
                        <input type="text" name="job_title" class="form-control form-control-alternative @error('job_title') is-invalid @enderror" value="{{ old('job_title', $activeContract->job_title) }}" placeholder="{{ __("Ex: Enseignant d'Informatique") }}" required>
                        @error('job_title') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Type de Contrat") }} <span class="text-danger">*</span></label>
                        <select name="contract_type" class="select2-modal @error('contract_type') is-invalid @enderror" data-placeholder="{{ __("Sélectionner le type de contrat") }}" required>
                            <option value=""></option>
                            @foreach(['CDI', 'CDD', 'VACATAIRE', 'PRESTATAIRE', 'STAGE'] as $type)
                                <option value="{{ $type }}" {{ old('contract_type', strtoupper($activeContract->contract_type)) == $type ? 'selected' : '' }}>
                                    {{ ucfirst(strtolower($type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_type') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Mode de rémunération") }} <span class="text-danger">*</span></label>
                        <select name="pay_type" class="select2-modal @error('pay_type') is-invalid @enderror" data-placeholder="{{ __("Sélectionner le mode") }}" required>
                            <option value=""></option>
                            <option value="monthly" {{ old('pay_type', $activeContract->pay_type) == 'monthly' ? 'selected' : '' }}>{{ __("Mensuel (Salaire Fixe)") }}</option>
                            <option value="hourly" {{ old('pay_type', $activeContract->pay_type) == 'hourly' ? 'selected' : '' }}>{{ __("Taux Horaire") }}</option>
                            <option value="forfait" {{ old('pay_type', $activeContract->pay_type) == 'forfait' ? 'selected' : '' }}>{{ __("Forfait") }}</option>
                        </select>
                        @error('pay_type') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Montant / Taux") }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_salary_or_rate" class="form-control form-control-alternative @error('base_salary_or_rate') is-invalid @enderror" value="{{ old('base_salary_or_rate', $activeContract->base_salary_or_rate) }}" placeholder="{{ __("Ex: 250000") }}" required>
                        @error('base_salary_or_rate') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Date de début") }} <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control form-control-alternative @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($activeContract->start_date)->format('Y-m-d')) }}" required>
                        @error('start_date') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold text-dark">{{ __("Remplacer le document scanné du contrat") }}</label>
                        <input type="file" name="contract_document" class="form-control form-control-alternative @error('contract_document') is-invalid @enderror" accept=".pdf,.png,.jpg,.jpeg">
                        @if($activeContract->contract_document_path)
                            <small class="form-text text-muted mt-1">
                                <i class="fa fa-paperclip"></i> {{ __("Un document existe déjà pour ce contrat.") }}
                            </small>
                        @endif
                        @error('contract_document') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('schedule.staff.index') }}" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> {{ __('Fermer') }}</a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>

<style>
/* Reset spécifique Gradient Able pour Select2 */
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single {
    background-color: #ffffff !important;
    background-image: none !important;
    background: #ffffff !important;
    border: 1px solid #cccccc !important;
    border-radius: 4px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered {
    background-color: transparent !important;
    background-image: none !important;
    background: transparent !important;
    color: #495057 !important;
    line-height: normal !important;
    padding-left: 0.75rem !important;
    padding-right: 2rem !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin: 0 !important;
    display: block !important;
    width: 100% !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #888888 !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
    height: 100% !important;
    margin-right: 20px !important;
    display: flex !important;
    align-items: center !important;
    background: transparent !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
    right: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    background-image: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    position: static !important;
    margin: 0 !important;
    border-color: #666 transparent transparent transparent !important;
}

.select2-container {
    width: 100% !important;
    display: block !important;
}

.select2-dropdown {
    z-index: 1060 !important;
    border-color: #cccccc !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
}
</style>

<script>
function previewImageEdit(input) {
    var preview = document.getElementById('avatar-preview-edit');
    var placeholder = document.getElementById('avatar-placeholder-edit');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.setProperty('display', 'none', 'important');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function() {
    // Initialisation individuelle de chaque Select2
    $('.select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');

        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });

    // Fermer les combos lors du défilement dans la modale
    $('.modal, .modal-body').on('scroll', function() {
        $('.select2-modal').select2('close');
    });
});
</script>