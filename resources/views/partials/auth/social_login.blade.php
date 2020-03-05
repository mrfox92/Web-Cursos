<div class="col-md-4">
    <div class="card animated fadeIn">
        <div class="card-header">{{ __("Socialite") }}</div>
        <div class="card-body">
            <a class="btn btn-github btn-lg btn-block" href="{{ route('social_auth', ['driver' => 'github']) }}">
                {{ __("Github") }} <i class="fa fa-github"></i>
            </a>

            <a class="btn btn-facebook btn-lg btn-block" href="{{ route('social_auth', ['driver' => 'facebook']) }}">
                {{ __("Facebook") }} <i class="fa fa-facebook"></i>
            </a>

            <a class="btn btn-google btn-lg btn-block" href="{{ route('social_auth', ['driver' => 'google']) }}">
                {{ __("Google") }} <i class="fa fa-google" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>