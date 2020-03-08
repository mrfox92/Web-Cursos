@extends('layouts.app')

@section('jumbotron')
    @include('partials.courses.jumbotron');
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <dir class="row justify-content-center">
            {{--  incluimos los objetivos del curso y le pasamos la data a traves de course->goals  --}}
            @include('partials.courses.goals', ['goals' => $course->goals])
            @include('partials.courses.requirements', ['requirements' => $course->requirements])
            @include('partials.courses.description')
            @include('partials.courses.related')
        </dir>
    </div>
@endsection 