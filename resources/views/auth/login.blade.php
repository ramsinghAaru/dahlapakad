@extends('layouts.app')

@section('content')
<div class="container px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm my-3 my-md-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Welcome Back!') }}</h5>
                    <p class="mb-0 small">{{ __('Sign in to continue to your account') }}</p>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (Route::has('register'))
                        <div class="text-center mb-4">
                            <p class="text-muted">
                                {{ __("Don't have an account?") }}
                                <a href="{{ route('register') }}" class="text-primary fw-medium">{{ __('Sign up') }}</a>
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email or Username') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input id="login" type="text" 
                                       class="form-control @error('email') is-invalid @enderror @error('username') is-invalid @enderror" 
                                       name="login" 
                                       value="{{ old('email') ?? old('username') }}" 
                                       placeholder="Enter your email or username" 
                                       required 
                                       autocomplete="email" 
                                       autofocus>
                                <div class="invalid-feedback">
                                    {{ __('Please enter your email or username.') }}
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-muted">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="Enter your password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    {{ __('Please enter your password.') }}
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-sign-in me-2"></i>{{ __('Sign In') }}
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-3">{{ __('Or sign in with') }}</p>
                            <div class="d-flex justify-content-center gap-2 gap-sm-3">
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2" aria-label="Sign in with Google">
                                    <i class="fa fa-google fa-lg"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2" aria-label="Sign in with Facebook">
                                    <i class="fa fa-facebook fa-lg"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2" aria-label="Sign in with Twitter">
                                    <i class="fa fa-twitter fa-lg"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            {{ __("By signing in, you agree to our") }}
                            <a href="{{ route('terms') }}" class="text-primary">{{ __('Terms of Service') }}</a>
                            {{ __('and') }}
                            <a href="{{ route('privacy') }}" class="text-primary">{{ __('Privacy Policy') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Form validation
(function () {
    'use strict'
    
    // Fetch the form we want to apply custom Bootstrap validation styles to
    var form = document.querySelector('.needs-validation')
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
    
    // Form submission validation
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
})();
</script>
@endpush
@endsection
