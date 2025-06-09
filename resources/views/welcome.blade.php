<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                Wypożyczalnia aut - Strona główna
            </h2>

            <div class="space-x-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                            Wyloguj się
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                        Logowanie
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm ml-2">
                            Rejestracja
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <h2 class="text-xl font-semibold text-center text-gray-900 dark:text-gray-100 mb-6">
            Dostępne samochody
        </h2>

        @if(isset($cars) && $cars->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <table class="w-full table-auto border border-gray-300 dark:border-gray-700 text-left">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border px-4 py-2 text-gray-600 dark:text-gray-300">Marka</th>
                            <th class="border px-4 py-2 text-gray-600 dark:text-gray-300">Model</th>
                            <th class="border px-4 py-2 text-gray-600 dark:text-gray-300">Rok</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($cars as $car)
                            <tr>
                                <td class="border px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->maker }}</td>
                                <td class="border px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->model }}</td>
                                <td class="border px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->year }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-700 dark:text-gray-300">
                Brak dostępnych samochodów.
            </p>
        @endif

    </div>

    <footer class="text-center text-sm text-gray-500 mt-10 dark:text-gray-400">
        &copy; {{ date('Y') }} Wypożyczalnia aut. Wszelkie prawa zastrzeżone.
    </footer>
</x-app-layout>
