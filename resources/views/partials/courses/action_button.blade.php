<div class="col-2">

    @auth
    {{--  Identificar usuario autenticado  --}}
        @can('opt_for_course', $course)
            {{--  Puede optar por un curso  --}}
            @can('subscribe', \App\Course::class)
                {{--  Se puede suscribir porque no tiene un plan contratado  --}}
                <a href="{{ route('subscriptions.plans') }}" class="btn btn-subscribe btn-bottom btn-block">
                    <i class="fa fa-bolt"></i> {{ __("Suscribirme") }}
                </a>
            @else
                {{--  No se puede suscribir, ya que tiene un plan contratado o es usuario administrador  --}}
                @can('inscribe', $course)
                    {{--  Puede apuntarse al curso  --}}
                    <a href="{{ route('courses.inscribe', ['course' => $course->slug]) }}" class="btn btn-subscribe btn-bottom btn-block">
                        <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                    </a>
                @else
                    {{--  Ya esta apuntado al curso  --}}
                    <a href="#" class="btn btn-subscribe btn-bottom btn-block">
                        <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                    </a>
                @endcan
            @endcan
        @else
            {{--  No Puede optar por un curso  --}}
            <a href="#" class="btn btn-subscribe btn-bottom btn-block">
                <i class="fa fa-user"></i> {{ __("Soy Autor") }}
            </a>
        @endcan
    @else
        {{--  No se pudo Identificar - no hay usuario autenticado  --}}
        <a href="{{ route('login') }}" class="btn btn-subscribe btn-bottom btn-block">
            <i class="fa fa-user"></i> {{ __("Acceder") }}
        </a>
    @endauth
</div>