<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel Administratora
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <p>Wybierz tabelę, aby wyświetlić dane:</p>

                <div class="mt-6 space-x-4">
                    <a href="{{ route('admin.table', ['name' => 'users']) }}" 
                       class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                        Users
                    </a>

                    <a href="{{ route('admin.table', ['name' => 'cars']) }}" 
                       class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                        Cars
                    </a>

                    <a href="{{ route('admin.table', ['name' => 'reservations']) }}" 
                       class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                        Reservations
                    </a>

                    <a href="{{ route('admin.table', ['name' => 'rentals']) }}" 
                       class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                        Rentals
                    </a>

                    <a href="{{ route('admin.table', ['name' => 'payments']) }}" 
                       class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                        Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
