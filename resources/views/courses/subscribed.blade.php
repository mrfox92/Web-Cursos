@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', [
        'title' =>  'Mis Cursos',
        'icon'  =>  'graduation-cap'
    ])
@endsection

@section('content')
    <div class="ml-5 mr-5">
        <div class="row justify-content-center">
            @forelse ($courses as $course)
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    @include('partials.courses.card_course')
                </div>
            @empty
                <div class="alert alert-dark">
                    {{ __("No tienes ningún curso suscrito") }}
                </div>
            @endforelse
        </div>
    </div>
@endsection