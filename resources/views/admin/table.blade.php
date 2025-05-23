<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dane z tabeli: {{ ucfirst($name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                <div class="mb-4">
                    <a href="{{ route('admin.table.create', ['name' => $name]) }}"
                       class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Dodaj nowy rekord
                    </a>
                </div>

                @if($data->isEmpty())
                    <p>Brak danych w tej tabeli.</p>
                @else
                    @php
                        // Klucze główne do identyfikacji rekordu, dopasuj do kontrolera
                        $primaryKeys = [
                            'users' => 'user_id',
                            'cars' => 'plate_number',
                            'reservations' => 'reservation_id',
                            'rentals' => 'rental_id',
                            'payments' => 'payment_id',
                        ];
                        $pk = $primaryKeys[$name] ?? null;
                    @endphp

                    <table 
                        class="w-full table-auto border-collapse border border-gray-300 text-sm"
                        style="table-layout: fixed;"
                    >
                        <thead>
                            <tr>
                                @foreach(array_keys((array) $data->first()) as $column)
                                    <th class="border border-gray-300 px-2 py-1 text-center font-bold break-words">
                                        {{ $column }}
                                    </th>
                                @endforeach
                                <th class="border border-gray-300 px-2 py-1 text-center font-bold">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                @php
                                    $id = $row->$pk;
                                @endphp
                                <tr>
                                    @foreach((array) $row as $value)
                                        <td class="border border-gray-300 px-2 py-1 break-words">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                    <td class="border border-gray-300 px-2 py-1 text-center">
                                        <a href="{{ route('admin.table.edit', ['name' => $name, 'id' => $id]) }}" 
                                           class="inline-block bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 mr-1">
                                            Edytuj
                                        </a>

                                        <form action="{{ route('admin.table.delete', ['name' => $name, 'id' => $id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Na pewno usunąć rekord?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                                Usuń
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
