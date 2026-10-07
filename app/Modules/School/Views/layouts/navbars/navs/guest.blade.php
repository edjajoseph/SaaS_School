<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#pablo">
        <img class="d-flex align-self-center img-radius" src="{{ global_asset('school/assets/img/iges-logo.jpg') }}" height="100" width="100" alt="Logo Scolaire">
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
      <span class="navbar-toggler-bar navbar-kebab"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navigation">
      <ul class="navbar-nav">
        <!--<li class="nav-item">
          <a href="" class="nav-link">
            <i class="now-ui-icons design_app"></i> {{ __("PARENT D'ELEVE") }}
          </a>
        </li>
        <li class="nav-item @if (($activePage ?? '') == 'register') active @endif">
          <a href="{{ route('register') }}" class="nav-link">
            <i class="now-ui-icons tech_mobile"></i> {{ __("ELEVE") }}
          </a>
        </li>--> 
        <li class="nav-item @if (($activePage ?? '') == 'login') active @endif">
          <!-- Lien ouvrant la popup (modale) via data-toggle -->
          <a href="#" class="nav-link" data-toggle="modal" data-target="#loginAdminModal">
            <i class="now-ui-icons users_circle-08"></i> {{ __("Se Connecter") }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<!-- Modal Connexion Administration -->
<div class="modal fade" id="loginAdminModal" tabindex="-1" role="dialog" aria-labelledby="loginAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
    <div class="modal-header text-center position-relative w-100 pr-5">
      <h4 class="title modal-title w-100 mt-2 mb-0" id="loginAdminModalLabel">
        {{ __('Connexion Administration') }}
      </h4>
      <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; z-index: 10;">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

      <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="modal-body px-4">
          
          <!-- Champ Email -->
          <div class="input-group no-border form-control-lg @error('email') has-danger @enderror">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="now-ui-icons users_circle-08"></i>
              </div>
            </div>
            <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('email') }}" required autofocus>
          </div>
          @error('email')
            <span class="text-danger small pl-2 mb-2 d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror

          <!-- Champ Mot de passe -->
          <div class="input-group no-border form-control-lg mt-3 @error('password') has-danger @enderror">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="now-ui-icons objects_key-25"></i>
              </div>
            </div>
            <input type="password" name="password" class="form-control" placeholder="{{ __('Mot de passe...') }}" required>
          </div>
          @error('password')
            <span class="text-danger small pl-2 mb-2 d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror

          <!-- Souvenez-vous de moi -->
          <div class="form-check text-left mt-3">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <span class="form-check-sign"></span>
              {{ __('Se souvenir de moi') }}
            </label>
          </div>

        </div>

        <div class="modal-footer justify-content-center flex-column pb-4">
          <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">{{ __('Se connecter') }}</button>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="link footer-link mt-2">{{ __('Mot de passe oublié ?') }}</a>
          @endif
        </div>
      </form>

    </div>
  </div>
</div>