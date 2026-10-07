<div class="modal-header bg-primary text-white">
    <h5 class="modal-title"><i class="fa fa-door-open mr-2"></i>Ajouter une Salle</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form method="POST" action="{{ route('schedule.rooms.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="row">
            @if($isAdmin)
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Établissement <span class="text-danger">*</span></label>
                <select name="school_id" class="form-control" required>
                    <option value="">Sélectionner un établissement</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Nom de la salle <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Salle 101, Amphi A" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Code / Identifiant</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Ex: S-101">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Capacité (Places)</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="1" placeholder="Ex: 30">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Bâtiment</label>
                <input type="text" name="building" class="form-control" value="{{ old('building') }}" placeholder="Ex: Bâtiment B">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Étage</label>
                <input type="number" name="floor" class="form-control" value="{{ old('floor') }}" placeholder="Ex: 1">
            </div>

            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active_create" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold" for="is_active_create">Activer immédiatement la salle</label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" onclick="$(this).closest('.modal').modal('hide');">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Enregistrer</button>
    </div>
</form>