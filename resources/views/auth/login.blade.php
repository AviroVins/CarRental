@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4>Zaloguj się do systemu</h4>
            </div>

            <div class="card-body">

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Adres email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Zapamiętaj mnie</label>
                    </div>

                    <!-- Forgot password -->
                    <div class="form-group d-flex justify-content-between">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="btn btn-link">Nie pamiętasz hasła?</a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-block">
                        Zaloguj się
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
