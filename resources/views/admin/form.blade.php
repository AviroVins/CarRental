<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $isEdit ? 'Edytuj rekord' : 'Dodaj nowy rekord' }} w tabeli: {{ ucfirst($name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                @if($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ $isEdit ? route('admin.table.update', ['name' => $name, 'id' => $record[$primaryKey]]) : route('admin.store', ['name' => $name]) }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    @foreach($columns as $column)
                        @php
                            // Pomiń kolumnę klucza głównego lub inne kolumny typu timestamp (opcjonalnie)
                            if(in_array($column, $excludeColumns)) continue;

                            // Wartość pola (edit - z rekordu, create - pusta)
                            $value = old($column, $isEdit ? $record[$column] : '');
                        @endphp

                        <div class="mb-4">
                            <label for="{{ $column }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">
                                {{ ucfirst(str_replace('_', ' ', $column)) }}
                            </label>
                            <input
                                type="text"
                                name="{{ $column }}"
                                id="{{ $column }}"
                                value="{{ $value }}"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                @if($column === $primaryKey && $isEdit) readonly @endif
                            >
                        </div>
                    @endforeach

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.table', ['name' => $name]) }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            Anuluj
                        </a>

                        <button type="submit" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ $isEdit ? 'Aktualizuj' : 'Dodaj' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
