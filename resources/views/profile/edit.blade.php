<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-200">
            Mój profil
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-4 lg:px-0 space-y-8"> <!-- max-w-xl = jeszcze węższy, bardziej "menu profilu" -->

            @if(session('success'))
                <div class="mb-4 text-green-600 text-center font-semibold">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Sekcja: Zdjęcie profilowe -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Zdjęcie profilowe</h3>

                    <div class="flex flex-col md:flex-row md:items-center md:space-x-6 space-y-4 md:space-y-0">
                        <div class="flex-shrink-0 self-center">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Zdjęcie profilowe"
                                    class="rounded-full object-cover border border-gray-300 dark:border-gray-600 w-28 h-28"
                                    style="width: 112px; height: 112px;">
                            @else
                                <div class="w-28 h-28 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 text-xl"
                                    style="width: 112px; height: 112px;">
                                    Brak
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="profile_photo" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Zmień zdjęcie</label>
                            <input type="file" name="profile_photo" id="profile_photo" 
                                class="block text-sm text-gray-900 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded cursor-pointer p-1 w-48">
                            @error('profile_photo') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Sekcja: Informacje podstawowe -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Informacje podstawowe</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Imię</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('first_name') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nazwisko</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('last_name') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Sekcja: Dane kontaktowe -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Dane kontaktowe</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('email') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Telefon</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('phone_number') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Sekcja: Ustawienia konta -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Ustawienia konta</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="has_driver_license" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Posiada prawo jazdy?</label>
                            <select name="has_driver_license" id="has_driver_license" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="1" {{ old('has_driver_license', $user->has_driver_license) ? 'selected' : '' }}>Tak</option>
                                <option value="0" {{ !old('has_driver_license', $user->has_driver_license) ? 'selected' : '' }}>Nie</option>
                            </select>
                            @error('has_driver_license') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="account_status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Status konta</label>
                            <select name="account_status" id="account_status" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="active" {{ old('account_status', $user->account_status) === 'active' ? 'selected' : '' }}>Aktywny</option>
                                <option value="inactive" {{ old('account_status', $user->account_status) === 'inactive' ? 'selected' : '' }}>Nieaktywny</option>
                                <option value="blocked" {{ old('account_status', $user->account_status) === 'blocked' ? 'selected' : '' }}>Zablokowany</option>
                            </select>
                            @error('account_status') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Rola</label>
                            <select name="role" id="role" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Użytkownik</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Przycisk zapisz -->
                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition">
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>