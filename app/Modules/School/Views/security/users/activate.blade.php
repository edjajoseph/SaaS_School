<div class="middle-box text-center loginscreen animated fadeInDown">
    <div class="card shadow-sm border-0 p-4">
        <div class="card-body">
            <div class="mb-3 text-primary">
                <i class="fa fa-shield fa-3x"></i>
            </div>
            <h4 class="font-weight-bold text-dark">{{ __("Activation du compte") }}</h4>
            <p class="text-muted small mb-4">
                {{ __("Pour finaliser l'activation du compte") }} <strong class="text-dark">{{ $user->email }}</strong>, 
                {{ __("veuillez définir votre nouveau mot de passe personnel.") }}
            </p>

            @if ($errors->any())
                <div class="alert alert-danger text-left py-2 mb-3">
                    <ul class="mb-0 pl-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.activate.submit', $user->id) }}" autocomplete="off">
                @csrf

                <div class="form-group mb-3">
                    <input type="password" 
                           name="password" 
                           class="form-control form-control-alternative @error('password') is-invalid @enderror" 
                           placeholder="Nouveau mot de passe" 
                           required>
                </div>

                <div class="form-group mb-3">
                    <input type="password" 
                           name="password_confirmation" 
                           class="form-control form-control-alternative" 
                           placeholder="Confirmer le nouveau mot de passe" 
                           required>
                </div>

                <div class="alert alert-light text-left border py-2 mb-4">
                    <small class="text-muted">
                        <i class="fa fa-info-circle mr-1"></i> Le mot de passe doit contenir au moins 8 caractères, des majuscules, des chiffres et des symboles.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-round waves-effect shadow-sm">
                    <i class="fa fa-check mr-1"></i> {{ __("Activer mon compte et enregistrer") }}
                </button>
            </form>
        </div>
    </div>
</div>