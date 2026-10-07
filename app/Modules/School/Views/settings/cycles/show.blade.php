<div class="page-body">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>FICHE CYCLE</h5>
                </div>
                <div class="card-body">
                    <p><strong>Code :</strong> <span class="badge badge-primary">{{ $cycle->code }}</span></p>
                    <p><strong>Libellé :</strong> {{ $cycle->name }}</p>
                    <p><strong>Ordre d'affichage :</strong> {{ $cycle->sequence_order }}</p>
                    <p><strong>Description :</strong> {{ $cycle->description ?: 'Aucune description' }}</p>
                    <hr>
                    <a href="{{ route('academic.cycles.index') }}" class="btn btn-warning btn-sm btn-round waves-effect">
                        <i class="fa fa-undo"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>NIVEAUX ASSOCIÉS</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom du niveau</th>
                                <th>Ordre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cycle->levels as $level)
                                <tr>
                                    <td>{{ $level->code }}</td>
                                    <td>{{ $level->name }}</td>
                                    <td>{{ $level->sequence_order }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun niveau rattaché à ce cycle.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>