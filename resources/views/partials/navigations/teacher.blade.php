<li class="nav-item"><a href="{{ route('profile.index') }}" class="nav-link">{{ __("Mi perfil") }}</a></li>
<li class="nav-item"><a href="{{ route('subscriptions.admin') }}" class="nav-link">{{ __("Mis suscripciones") }}</a></li>
<li class="nav-item"><a href="{{ route('invoices.admin') }}" class="nav-link">{{ __("Mis facturas") }}</a></li>
<li class="nav-item"><a href="{{ route('courses.subscribed') }}" class="nav-link">{{ __("Mis cursos") }}</a></li>
<li class="nav-item"><a href="{{ route('teacher.courses') }}" class="nav-link">{{ __("Cursos desarrollados por mi") }}</a></li>
<li class="nav-item"><a href="{{ route('courses.create') }}" class="nav-link">{{ __("Crear curso") }}</a></li>
@include('partials.navigations.logged')