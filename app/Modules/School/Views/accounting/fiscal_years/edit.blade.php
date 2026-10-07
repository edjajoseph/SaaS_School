<div class="col-md-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-edit text-warning mr-2"></i>Modifier Exercice Comptable</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school.accounting.fiscal-years.update', $fiscalYear->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Sélection Année Académique -->
                <div class="form-group">
                    <label class="font-weight-bold">Année Académique <span class="text-danger">*</span></label>
                    <select name="academic_year_id" class="form-control @error('academic_year_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionner une année académique --</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ old('academic_year_id', $fiscalYear->academic_year_id) == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }} ({{ $ay->start_date->format('d/m/Y') }} - {{ $ay->end_date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Libellé -->
                <div class="form-group">
                    <label class="font-weight-bold">Nom / Libellé de l'exercice</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $fiscalYear->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $fiscalYear->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Date de fin <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $fiscalYear->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Statut <span class="text-danger">*</span></label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="open" {{ old('status', $fiscalYear->status) === 'open' ? 'selected' : '' }}>Ouvert</option>
                        <option value="closed" {{ old('status', $fiscalYear->status) === 'closed' ? 'selected' : '' }}>Clôturé</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-right">
                    <a href="{{ route('school.accounting.fiscal-years.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                    <button type="submit" class="btn btn-warning text-white"><i class="fa fa-sync mr-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>