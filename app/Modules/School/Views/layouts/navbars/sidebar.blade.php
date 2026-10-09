<div class="sidebar" data-color="orange">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
  <div class="logo">
    <a href="" class="simple-text logo-mini">
      {{ __() }}
    </a>
    <a href="" class="simple-text logo-normal">
    {{ Auth::user()->name }}
    </a>
  </div>
  <div class="sidebar-wrapper" id="sidebar-wrapper">
    <ul class="nav">      
      <li class="@if ($activePage == 'home') active @endif">
        <a href="{{ route('tenant.home', ['tenant' => tenant()->getTenantKey()]) }}">
          <i class="now-ui-icons design_app"></i>
          <p>{{ __('Tableau de bord') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'pays') active @endif">
        <a href="{{ route('pays.index') }}">
          <i class="now-ui-icons business_globe"></i>
          <p>{{ __('Gestion des pays') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'ville') active @endif">
        <a href="{{ route('ville.index') }}">
          <i class="now-ui-icons business_bank"></i>
          <p>{{ __('Gestion des villes') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'commune') active @endif">
        <a href="{{ route('commune.index') }}">
          <i class="now-ui-icons arrows-1_share-66"></i>
          <p>{{ __('Gestion des communes') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'etablissement') active @endif">
        <a href="{{ route('etablissement.index') }}">
          <i class="now-ui-icons shopping_shop"></i>
          <p>{{ __('Gestion des établissements') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'fonction') active @endif">
        <a href="{{ route('fonction.index') }}">
          <i class="now-ui-icons education_hat"></i>
          <p>{{ __('Gestion des fonctions') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'emploi') active @endif">
        <a href="{{ route('emploi.index') }}">
          <i class="now-ui-icons objects_umbrella-13"></i>
          <p>{{ __('Gestion des emplois') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'niveau') active @endif">
        <a href="{{ route('niveau.index') }}">
          <i class="now-ui-icons objects_umbrella-13"></i>
          <p>{{ __('Gestion des niveaux') }}</p>
        </a>
      </li>
      <li class="@if ($activePage == 'personnels') active @endif">
        <a href="{{ route('personnel.index') }}">
          <i class="now-ui-icons business_briefcase-24"></i>
          <p> {{ __("Gestion du personnel") }} </p>
        </a>
      </li>
      <li class="@if ($activePage == 'roles') active @endif">
        <a href="{{ route('role.index') }}">
          <i class="now-ui-icons ui-1_lock-circle-open"></i>
          <p> {{ __("Gestion des rôles") }} </p>
        </a>
      </li>
      <li class="@if ($activePage == 'users') active @endif">
        <a href="{{ route('user.index') }}">
          <i class="now-ui-icons users_single-02"></i>
          <p> {{ __("Gestion des comptes") }} </p>
        </a>
      </li>
      
      <!--  <li>
        <a data-toggle="collapse" href="#laravelExamples">
            <i class="fab fa-laravel"></i>
          <p>
            {{ __("Laravel Examples") }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse show" id="laravelExamples">
          <ul class="nav">
            <li class="@if ($activePage == 'profile') active @endif">
              <a href="{{ route('profile.edit') }}">
                <i class="now-ui-icons users_single-02"></i>
                <p> {{ __("User Profile") }} </p>
              </a>
            </li>
            <li class="@if ($activePage == 'users') active @endif">
              <a href="{{ route('user.index') }}">
                <i class="now-ui-icons design_bullet-list-67"></i>
                <p> {{ __("User Management") }} </p>
              </a>
            </li>
          </ul>
        </div>
     <li class="@if ($activePage == 'icons') active @endif">
        <a href="{{ route('page.index','icons') }}">
          <i class="now-ui-icons education_atom"></i>
          <p>{{ __('Icons') }}</p>
        </a>
      </li>
      <li class = "@if ($activePage == 'maps') active @endif">
        <a href="{{ route('page.index','maps') }}">
          <i class="now-ui-icons location_map-big"></i>
          <p>{{ __('Maps') }}</p>
        </a>
      </li>
      <li class = " @if ($activePage == 'notifications') active @endif">
        <a href="{{ route('page.index','notifications') }}">
          <i class="now-ui-icons ui-1_bell-53"></i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      <li class = " @if ($activePage == 'table') active @endif">
        <a href="{{ route('page.index','table') }}">
          <i class="now-ui-icons design_bullet-list-67"></i>
          <p>{{ __('Table List') }}</p>
        </a>
      </li>
      <li class = "@if ($activePage == 'typography') active @endif">
        <a href="{{ route('page.index','typography') }}">
          <i class="now-ui-icons text_caps-small"></i>
          <p>{{ __('Typography') }}</p>
        </a>
      </li>
      <li class = "">
        <a href="{{ route('page.index','upgrade') }}" class="bg-info">
          <i class="now-ui-icons arrows-1_cloud-download-93"></i>
          <p>{{ __('Upgrade to PRO') }}</p>
        </a>
      </li> -->
    </ul>
  </div>
</div>
