@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', [
        'title'     =>  'Cursos que imparto',
        'icon'      =>  'building'
    ])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            @forelse ($courses as $course)
                <div class="col-md-8 listing-block">
                    <div class="media" style="height: 200px;">
                        <img
                            src="{{ $course->pathAttachment() }}"
                            class="img-rounded"
                            alt="{{ $course->name }}"
                            style="height: 200px; width: 300px"
                        >
                        <div class="media-body pl-3" style="height:200px;">
                            <div class="price">
                                <small class="badge-danger text-white text-center">
                                {{ $course->category->name }}
                                </small>
                                <small>{{ __("Curso") }}: {{ $course->name }}</small>
                                <small>{{ __("Estudiantes") }}: {{ $course->students_count }}</small>
                            </div>

                            <div class="stats">
                                {{ $course->created_at->format('d/m/Y') }}
                                @include('partials.courses.rating', ['rating' => $course->custom_rating])
                            </div>

                            @include('partials.courses.teacher_action_buttons')
                        </div>
                    </div>
                </div>
            @empty

                <div class="alert alert-dark">
                    {{ __("No tienes ningún curso todavía") }}

                    <a href="{{ route('courses.create') }}" class="btn btn-course btn-block">
                        {{ __("Crear mi primer curso!") }}
                    </a>
                </div>
                
            @endforelse
            
        </div>
        <div class="row justify-content-center">
            {{ $courses->links() }}
        </div>
    </div>
@endsection