
<div class="col-md-12 pt-0 mt-0">
    <h2 class="text-muted">{{ __("Requisitos del curso") }}</h2>
    <hr>
</div>
@forelse ($requirements as $requirement)
    <div class="col-md-6">
        <div class="card border-primary p-3">
            <p class="mb-0">
                {{ $requirement->requirement }}
            </p>
        </div>
    </div>
@empty
    <div class="alert alert-dark">
        <i class="fa fa-info-circle"></i>
        {{ __("No hay ningún requisito para este curso") }}
    </div>
@endforelse