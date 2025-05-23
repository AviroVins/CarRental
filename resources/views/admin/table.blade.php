<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dane z tabeli: {{ ucfirst($name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                @if($data->isEmpty())
                    <p>Brak danych w tej tabeli.</p>
                @else
                    <table 
                        class="w-full table-auto border-collapse border border-gray-300 text-sm"
                        style="table-layout: fixed;"
                    >
                        <thead>
                            <tr>
                                @foreach(array_keys((array) $data->first()) as $column)
                                    <th class="border border-gray-300 px-2 py-1 text-center text-bold  break-words">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    @foreach((array) $row as $value)
                                        <td class="border border-gray-300 px-2 py-1 break-words">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
