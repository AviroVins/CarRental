<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Edytuj użytkownika
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('users.update', $user->user_id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium">Imię</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Rola</label>
                    <select name="role" class="w-full border px-3 py-2">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Użytkownik</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Zaktualizuj
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
