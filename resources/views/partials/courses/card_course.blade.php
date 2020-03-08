<div class="card animated fadeIn" style="width: 18rem;">
    <img src="{{ $course->pathAttachment() }}" class="card-img-top" alt="{{ $course->name }}">
    <div class="card-body">
      <h5 class="card-title">{{ $course->name }}</h5>
      <hr>
      <div class="row justify-content-center">
          @include('partials.courses.rating')
      </div>
      <hr>
      <span class="badge badge-danger badge-cat">{{ $course->category->name }}</span>
        <p class="card-text my-2">
            {{ Str::limit($course->description, 100) }}
        </p>
      <a href="{{ route('courses.detail', $course->slug) }}" class="btn btn-primary btn-block">Ir al curso</a>
    </div>
  </div>