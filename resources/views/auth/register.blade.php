@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4>Rejestracja nowego użytkownika</h4>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- PESEL -->
                    <div class="form-group">
                        <label for="pesel">PESEL</label>
                        <input type="text" id="pesel" name="pesel" maxlength="11" class="form-control" value="{{ old('pesel') }}" required>
                        @error('pesel')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">Imię</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Nazwisko</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Adres email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone_number">Numer telefonu</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Has Driver License -->
                    <div class="form-group">
                        <label for="has_driver_license">Czy masz prawo jazdy?</label>
                        <select id="has_driver_license" name="has_driver_license" class="form-control" required>
                            <option value="">-- Wybierz --</option>
                            <option value="1" {{ old('has_driver_license') === '1' ? 'selected' : '' }}>Tak</option>
                            <option value="0" {{ old('has_driver_license') === '0' ? 'selected' : '' }}>Nie</option>
                        </select>
                        @error('has_driver_license')
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

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Potwierdź hasło</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Link to login -->
                    <div class="form-group d-flex justify-content-between">
                        <a href="{{ route('login') }}" class="btn btn-link">Masz już konto? Zaloguj się</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-block">
                        Zarejestruj się
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
