<li class="nav-item"><a href="{{ route('profile.index') }}" class="nav-link">{{ __("Mi perfil") }}</a></li>
<li class="nav-item"><a href="{{ route('subscriptions.admin') }}" class="nav-link">{{ __("Mis suscripciones") }}</a></li>
<li class="nav-item"><a href="{{ route('invoices.admin') }}" class="nav-link">{{ __("Mis facturas") }}</a></li>
<li class="nav-item"><a href="{{ route('courses.subscribed') }}" class="nav-link">{{ __("Mis cursos") }}</a></li>
@include('partials.navigations.logged')