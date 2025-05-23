<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Lista: {{ $title }}
        </h2>
    </x-slot>

    @php
        $idKey = array_key_first($columns);
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route($routePrefix . '.create') }}"
               class="mb-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Dodaj nowy
            </a>

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full text-left border border-gray-300">
                    <thead>
                        <tr>
                            @foreach($columns as $col)
                                <th class="border px-4 py-2">{{ ucfirst($col) }}</th>
                            @endforeach
                            <th class="border px-4 py-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                @foreach($columns as $col)
                                    <td class="border px-4 py-2">{{ $item->$col }}</td>
                                @endforeach
                                <td class="border px-4 py-2">
                                    <a href="{{ route($routePrefix . '.edit', $item) }}"
                                       class="text-blue-500 hover:underline mr-2">Edytuj</a>
                                    <form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Na pewno?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Usuń</button>
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
