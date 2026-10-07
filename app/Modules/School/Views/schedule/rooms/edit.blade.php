<div class="modal-header bg-info text-dark">
    <h5 class="modal-title"><i class="fa fa-edit mr-2"></i>Modifier la Salle : {{ $room->name }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form method="POST" action="{{ route('schedule.rooms.update', ['room' => $room->id]) }}" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            @if($isAdmin)
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Établissement <span class="text-danger">*</span></label>
                <select name="school_id" class="form-control" required>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $room->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
                <input type="hidden" name="school_id" value="{{ $room->school_id }}">
            @endif

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Nom de la salle <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $room->name) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Code / Identifiant</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $room->code) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Capacité (Places)</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}" min="1">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Bâtiment</label>
                <input type="text" name="building" class="form-control" value="{{ old('building', $room->building) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Étage</label>
                <input type="number" name="floor" class="form-control" value="{{ old('floor', $room->floor) }}">
            </div>

            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active_edit" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold" for="is_active_edit">Salle active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <a href="{{ route('schedule.rooms.index') }}" class="btn btn-secondary btn-round waves-effect"><i class="fa fa-times mr-1"></i> {{ __('Fermer') }}</a>
        <button type="submit" class="btn btn-success"><i class="fa fa-sync-alt mr-1"></i> Mettre à jour</button>
    </div>
</form>