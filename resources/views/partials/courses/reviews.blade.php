<div class="align-content-center">
    <div class="col-12 pt-0 mt-4">
        <h2 class="text-muted">{{ __("Valoraciones") }}</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            @forelse ($course->reviews as $review)
                <div class="col-md-8 offset-2 listing-block">
                    <div class="media">
                        <img class="img-rounded" src="{{ $review->user->pathAttachment() }}" alt="{{ $review->user->name }}">
                        <div class="media-body pl-3">
                            <div class="price">
                                @if( $review->comment )
                                    <div class="price">
                                        <small>{{ $review->comment }}</small>
                                    </div>
                                @endif

                                <div class="stats">
                                    {{ $review->created_at->format('d/m/Y') }}
                                    @include('partials.courses.rating', ['rating' => $review->rating])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-dark">
                    <i class="fa fa-info-circle"></i> {{ __("Sin Valoraciones todavía") }}
                </div>
            @endforelse
        </div>
    </div>
</div>