<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Lista użytkowników
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('users.create') }}"
               class="mb-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Dodaj użytkownika
            </a>

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full text-left border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Imię</th>
                            <th class="border px-4 py-2">Nazwisko</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Telefon</th>
                            <th class="border px-4 py-2">Rola</th>
                            <th class="border px-4 py-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->pesel }}</td>
                                <td class="border px-4 py-2">{{ $user->first_name }}</td>
                                <td class="border px-4 py-2">{{ $user->last_name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->phone_number }}</td>
                                <td class="border px-4 py-2">{{ $user->role }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('users.edit', $user->pesel) }}"
                                       class="text-blue-500 hover:underline mr-2">Edytuj</a>

                                    <form action="{{ route('users.destroy', $user->pesel) }}" method="POST"
                                          class="inline" onsubmit="return confirm('Czy na pewno chcesz usunąć?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
