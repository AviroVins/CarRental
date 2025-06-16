@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Mój profil</h1>

@if(session('success'))
    <div class="alert alert-success text-center font-weight-bold">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Zdjęcie profilowe -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">Zdjęcie profilowe</div>
        <div class="card-body d-flex align-items-center">
            @if($user->profile_photo)
                <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Zdjęcie profilowe"
                    class="rounded-circle border mr-4" width="112" height="112">
            @else
                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mr-4"
                    style="width: 112px; height: 112px;">
                    Brak
                </div>
            @endif

            <div>
                <label for="profile_photo" class="form-label">Zmień zdjęcie</label>
                <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                @error('profile_photo') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- Informacje podstawowe -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">Informacje podstawowe</div>
        <div class="card-body row">
            <div class="form-group col-md-6">
                <label for="first_name">Imię</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="{{ old('first_name', $user->first_name) }}">
                @error('first_name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="last_name">Nazwisko</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="{{ old('last_name', $user->last_name) }}">
                @error('last_name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- Dane kontaktowe -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">Dane kontaktowe</div>
        <div class="card-body row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}">
                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="phone_number">Telefon</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number"
                    value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- Ustawienia konta -->
    <div class="card mb-4">
        <div class="card-header font-weight-bold">Ustawienia konta</div>
        <div class="card-body row">
            <div class="form-group col-md-4">
                <label for="has_driver_license">Posiada prawo jazdy?</label>
                <select class="form-control" id="has_driver_license" name="has_driver_license">
                    <option value="1" {{ old('has_driver_license', $user->has_driver_license) ? 'selected' : '' }}>Tak</option>
                    <option value="0" {{ !old('has_driver_license', $user->has_driver_license) ? 'selected' : '' }}>Nie</option>
                </select>
                @error('has_driver_license') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="account_status">Status konta</label>
                <select class="form-control" id="account_status" name="account_status">
                    <option value="active" {{ old('account_status', $user->account_status) === 'active' ? 'selected' : '' }}>Aktywny</option>
                    <option value="inactive" {{ old('account_status', $user->account_status) === 'inactive' ? 'selected' : '' }}>Nieaktywny</option>
                    <option value="blocked" {{ old('account_status', $user->account_status) === 'blocked' ? 'selected' : '' }}>Zablokowany</option>
                </select>
                @error('account_status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="role">Rola</label>
                <select class="form-control" id="role" name="role">
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Użytkownik</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg">Zapisz zmiany</button>
    </div>
</form>
@endsection
