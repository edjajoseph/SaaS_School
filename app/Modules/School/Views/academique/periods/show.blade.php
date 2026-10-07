<div class="modal-body p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border mb-0">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('Année Académique') }}</label>
                            <span class="font-weight-bold text-dark font-16">
                                {{ $period->academicYear->name ?? $period->academicYear->code ?? '-' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('Type & Nom de période') }}</label>
                            <span class="badge badge-primary px-2 py-1 mr-1">
                                {{ $period->periodTypeItem->periodType->name ?? '' }}
                            </span>
                            <span class="font-weight-bold text-dark">
                                {{ $period->periodTypeItem->name ?? $period->name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('Date de début') }}</label>
                            <span class="text-success font-weight-bold">
                                <i class="fa fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('Date de fin') }}</label>
                            <span class="text-danger font-weight-bold">
                                <i class="fa fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('Période courante') }}</label>
                            @if($period->is_current)
                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Oui (Active)</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Non</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted font-weight-bold d-block">{{ __('État de la période') }}</label>
                            @if($period->is_closed)
                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-lock mr-1"></i>Clôturée</span>
                            @else
                                <span class="badge badge-info px-2 py-1"><i class="fa fa-unlock mr-1"></i>Ouverte</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <a href="{{ route('organisation.periods.index') }}" class="btn btn-secondary btn-round waves-effect">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </a>
</div>