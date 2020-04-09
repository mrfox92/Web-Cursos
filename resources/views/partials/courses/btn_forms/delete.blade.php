<form method="POST" action="{{ route('courses.destroy', ['course' => $course->slug]) }}">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger text-white">
        <i class="fa fa-trash"></i> {{ __("Eliminar curso") }}
    </button>
</form>