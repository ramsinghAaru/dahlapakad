@extends('layouts.app')

@section('content')
<div class="container px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm my-3 my-md-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">{{ __('Create an Account') }}</h5>
                    <p class="mb-0 small">{{ __('Join our community today') }}</p>
                </div>

                <div class="card-body p-3 p-md-4">
                    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    <div class="invalid-feedback">
                                        {{ __('Please enter your full name.') }}
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }} <span class="text-danger">*</span></label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" 
                                           name="username" value="{{ old('username') }}" required autocomplete="username">
                                    <div class="invalid-feedback">
                                        {{ __('Please choose a username.') }}
                                    </div>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">{{ __('This will be your unique identifier on the platform.') }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <div class="invalid-feedback">
                                        {{ __('Please enter a valid email address.') }}
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required autocomplete="new-password" 
                                           pattern=".{8,}" title="Password must be at least 8 characters">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        {{ __('Password must be at least 8 characters.') }}
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">{{ __('Must be at least 8 characters long.') }}</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input id="password-confirm" type="password" class="form-control" 
                                           name="password_confirmation" required autocomplete="new-password"
                                           data-match="#password" data-match-error="Passwords don't match">
                                    <div class="invalid-feedback">
                                        {{ __('Passwords must match.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" 
                                       name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    {{ __('I agree to the') }} <a href="{{ route('terms') }}" target="_blank" class="text-primary">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" target="_blank" class="text-primary">{{ __('Privacy Policy') }}</a> <span class="text-danger">*</span>
                                </label>
                                <div class="invalid-feedback">
                                    {{ __('You must agree to the terms and conditions.') }}
                                </div>
                                @error('terms')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-user-plus me-2"></i>{{ __('Create Account') }}
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                {{ __('Already have an account?') }}
                                <a href="{{ route('login') }}" class="text-primary fw-medium">{{ __('Sign In') }}</a>
                            </p>
                        </div>
                    </form>
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
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password-confirm');
    
    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
    
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
