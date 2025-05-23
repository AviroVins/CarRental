<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Panel administratora
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Tabele do zarządzania:</h3>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
                    <li><a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">Użytkownicy</a></li>
                    <li><a href="{{ route('cars.index') }}" class="text-blue-600 hover:underline">Samochody</a></li>
                    <li><a href="{{ route('reservations.index') }}" class="text-blue-600 hover:underline">Rezerwacje</a></li>
                    <li><a href="{{ route('rentals.index') }}" class="text-blue-600 hover:underline">Wypożyczenia</a></li>
                    <li><a href="{{ route('payments.index') }}" class="text-blue-600 hover:underline">Płatności</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
