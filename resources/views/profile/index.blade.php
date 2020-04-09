@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', [
        'title' =>  'Configurar tu perfil',
        'icon'  =>  'user-circle'
    ])
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endpush

@section('content')
<div class="pl-5 pr-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __("Actualiza tus datos") }}
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
                                {{ __("Correo electrónico") }}
                            </label>
                            <div class="col-md-6">
                                <input
                                    id="email"
                                    type="email"
                                    readonly
                                    name="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') ?: $user->email  }}"
                                    required
                                    autofocus>
                            </div>
                            @if( $errors->has('email') )
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
                                {{ __("Contraseña") }}
                            </label>
                            <div class="col-md-6">
                                <input
                                    type="password"
                                    name="password" 
                                    id="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    required
                                >

                                @if( $errors->has('password') )
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                {{ __("Confirma la contraseña") }}
                            </label>
                            <div class="col-md-6">
                                <input
                                    type="password"
                                    name="password_confirmation" 
                                    id="password-confirm"
                                    class="form-control"
                                    required
                                >

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __("Actualizar datos") }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if( ! $user->teacher )
                <div class="card">
                    <div class="card-header">
                        {{ __("Convertirme en profesor de la plataforma") }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('solicitude.teacher') }}" method="post">
                            @csrf
                            <button class="btn btn-outline-primary btn-block" type="submit">
                                <i class="fa fa-graduation-cap"></i> {{ __("Solicitar") }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        {{ __("Administrar los cursos que imparto") }}
                    </div>
                    <div class="card-body">
                        <a class="btn btn-secondary btn-block" href="{{ route('teacher.courses') }}">
                            <i class="fa fa-leanpub"></i> {{ __("Administrar ahora") }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        {{ __("Mis estudiantes") }}
                    </div>
                  <div class="card-body">
                      <table class="table table-stripped table-bordered nowrap" cellspacing="0" id="students-table">
                          <thead>
                              <tr>
                                  <th>{{ __("ID") }}</th>
                                  <th>{{ __("Nombre") }}</th>
                                  <th>{{ __("Email") }}</th>
                                  <th>{{ __("Cursos") }}</th>
                                  <th>{{ __("Acciones") }}</th>
                              </tr>
                          </thead>
                      </table>
                  </div>
                </div>
            @endif

            @if ( $user->socialAccount )
                <div class="card">
                    <div class="card-header">
                        {{ __("Acceso con Socialite") }}
                    </div>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-dark btn-block">
                        {{ __("Registrado con") }}: <i class="fa fa-{{ $user->socialAccount->provider }}"></i>
                        {{ $user->socialAccount->provider }}
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>
    @include('partials.modal')
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function() {
            let dt;
            let modal = $('#appModal');
            $.noConflict(); //  resolver conflictos al cargar varios archivos jquery
            dt = $('#students-table').DataTable({
                pageLength: 5,
                lengthMenu: [ 5, 10, 25, 50, 75, 100 ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('teacher.students') }}',
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                columns: [
                    { data: 'user.id' },
                    { data: 'user.name' },
                    { data: 'user.email' },
                    { data: 'courses_formatted' },
                    { data: 'actions' }
                ]

            });

            $(document).on('click', '.btnEmail', function( e ) {
                e.preventDefault();
                const id = $(this).data('id');  //  obtenemos el id del usuario que viene en la propiedad data-id
                modal.find('.modal-title').text('{{ __("Enviar mensaje") }}');
                modal.find('#modalAction').text('{{ __("Enviar mensaje") }}').show();

                let $form = $(`<form id="studentMessage"></form>`);
                $form.append(`<input type="hidden" name="user_id" value="${ id }" />`); //  añadimos campo oculto
                $form.append(`<textarea placeholder="tu mensaje ..." class="form-control" name="message"></textarea>`);
                modal.find('.modal-body').html($form);  //  insertamos en el body de nuestro modal el campo oculto y el textarea
                modal.modal('show');
            });

            $(document).on('click', '#modalAction', function( e ) {
                e.preventDefault();
                //  peticion ajax
                $.ajax({
                    url: '{{ route('teacher.send_message_to_student') }}',
                    type: 'POST',
                    headers: {
                        'x-csrf-token': $("meta[name=csrf-token]").attr('content')
                    },
                    data: {
                        info: $('#studentMessage').serialize()  //la data serializada enviada via ajax
                    },
                    success: (res) => {
                        if ( res.res ) {
                            modal.find('#modalAction').hide();
                            modal.find('.modal-body').html('<div class="alert alert-success">{{ __("Mensaje enviado correctamente") }}</div>');
                        } else {
                            modal.find('.modal-body').html('<div class="alert alert-danger">{{ __("Ha ocurrido un error al enviar tu mensaje") }}</div>');
                        }
                    }
                });
            });
        });
    </script>
@endpush