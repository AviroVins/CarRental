<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ $mode === 'create' ? 'Dodaj' : 'Edytuj' }} {{ $title }}
        </h2>
    </x-slot>

    @php
        $idKey = $item->getKeyName();
        $paramName = \Illuminate\Support\Str::singular($routePrefix);
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST"
                action="{{ $mode === 'create' ? route($routePrefix . '.store') : route($routePrefix . '.update', [$paramName => $item->$idKey]) }}">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                @foreach($columns as $col)
                    @if(!in_array($col, [$idKey, 'created_at', 'updated_at']))
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200" for="{{ $col }}">
                                {{ ucfirst(str_replace('_', ' ', $col)) }}
                            </label>
                            <input
                                type="text"
                                id="{{ $col }}"
                                name="{{ $col }}"
                                value="{{ old($col, $item->$col ?? '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white"
                            >
                        </div>
                    @endif
                @endforeach

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Zapisz
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
