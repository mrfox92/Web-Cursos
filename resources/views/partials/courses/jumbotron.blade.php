<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" style="background-image: url('{{ url('/images/jumbotron.png') }}');">
            <div class="d-flex align-items-center text-center text-white py-5 px-4 my-5">

                <div class="col-5">
                    <img class="img-fluid" src="{{ $course->pathAttachment() }}" alt="">
                </div>

                <div class="col-5 text-left">
                    <h1>{{ __("Curso") }}: {{ $course->name }}</h1>
                    <h4>{{ __("Profesor") }}: {{ $course->teacher->user->name }}</h4>
                    <h5>{{ __("Categoria") }}: {{ $course->category->name }}</h4>
                    <h5>{{ __("Fecha de publicación") }}: {{ $course->created_at->format('d/m/Y') }}</h4>
                    <h5>{{ __("Fecha de actualización") }}: {{ $course->updated_at->format('d/m/Y') }}</h4>
                    <h6>{{ __("Estudiantes inscritos") }}: {{ $course->students_count }}</h5>
                    <h6>{{ __("Número de valoraciones") }}: {{ $course->reviews_count }}</h5>
                    @include('partials.courses.rating')
                </div>

                @include('partials.courses.action_button')

            </div>
        </div>
    </div>
</div>