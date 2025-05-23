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
                                class="text-sm text-gray-700 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white">
                            Wyloguj się
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-700 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white">
                        Logowanie
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="text-sm text-gray-700 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white ml-4">
                            Rejestracja
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>

    <main class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <br>
        <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-gray-100 text-center">Dostępne samochody</h2>

        @if(isset($cars) && $cars->count() > 0)
            <div>
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 max-w-4xl w-full flex justify-center">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 ">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Marka</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Rok</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($cars as $car)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $car->maker }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $car->model }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $car->year }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">Brak dostępnych samochodów.</p>
        @endif
    </main>

    <footer class="text-center text-sm text-gray-500 mt-10 dark:text-gray-400">
        &copy; {{ date('Y') }} Wypożyczalnia aut. Wszelkie prawa zastrzeżone.
    </footer>
</x-app-layout>
