
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@include('School::layouts.navbars.sidebar')
<div class="main-panel">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('School::layouts.footer')
</div>