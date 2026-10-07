<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <h3 class="font-bold">{{ __("Activation & Modification du mot de passe") }}</h3>
        <p class="text-muted">
            {{ __("Pour finaliser l'activation du compte") }} <strong>{{ $user->email }}</strong>, 
            {{ __("veuillez définir votre nouveau mot de passe personnel.") }}
        </p>

        @if ($errors->any())
            <div class="alert alert-danger text-left">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="m-t" method="POST" action="{{ route('account.activate.submit', $user->id) }}" autocomplete="off">
            @csrf

            <div class="form-group mb-3">
                <input type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Nouveau mot de passe" 
                       required>
            </div>

            <div class="form-group mb-3">
                <input type="password" 
                       name="password_confirmation" 
                       class="form-control" 
                       placeholder="Confirmer le nouveau mot de passe" 
                       required>
            </div>

            <div class="alert alert-light text-left border py-2 mb-3">
                <small class="text-muted">
                    <i class="fa fa-info-circle"></i> Le mot de passe doit contenir au moins 8 caractères, des majuscules, des chiffres et des symboles.
                </small>
            </div>

            <button type="submit" class="btn btn-primary block full-width m-b">
                <i class="fa fa-check"></i> {{ __("Activer mon compte et enregistrer") }}
            </button>
        </form>
    </div>
</div>