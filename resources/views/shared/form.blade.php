<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ $title }}
        </h2>
    </x-slot>

    @php
        $idKey = $item->getKeyName();

        $lastSegment = collect(explode('.', $routePrefix))->last();
        $paramName = \Illuminate\Support\Str::singular($lastSegment);

        $extraData = $extraData ?? [];
        $forceInput = $forceInput ?? [];
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
                    @if($mode === 'create' && $col === 'rental_id')
                        @continue
                    @endif

                    @if(in_array($col, ['created_at', 'updated_at']))
                        @continue
                    @endif

                    <div class="mb-4">
                        <label for="{{ $col }}" class="block text-gray-700 dark:text-gray-200">
                            {{ ucfirst(str_replace('_', ' ', $col)) }}
                        </label>

                        @if($col === 'reservation_id')
                            <select id="{{ $col }}" name="{{ $col }}" required
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                <option value="">-- Wybierz rezerwację --</option>
                                @foreach($extraData['reservations'] ?? [] as $reservation_id => $label)
                                        <option value="{{ $reservation_id }}" {{ old($col, $item->$col ?? '') == $reservation_id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                            </select>

                        @elseif($col === 'rental_id')
                            <select id="{{ $col }}" name="{{ $col }}" required
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                <option value="">-- Wybierz wypożyczenie --</option>
                                @foreach($extraData['rentals'] ?? [] as $rental_id => $label)
                                        <option value="{{ $rental_id }}" {{ old($col, $item->$col ?? '') == $rental_id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                            </select>

                        @elseif($col === 'pesel')
                            @if(in_array($col, $forceInput) || $routePrefix === 'users')
                                <input type="text" id="{{ $col }}" name="{{ $col }}" required
                                    value="{{ old($col, $item->$col ?? '') }}"
                                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                            @else
                                <select id="{{ $col }}" name="{{ $col }}" required
                                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                    <option value="">-- Wybierz użytkownika --</option>
                                    @foreach($extraData['users'] ?? [] as $pesel => $label)
                                        <option value="{{ $pesel }}" {{ old($col, $item->$col ?? '') == $pesel ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                        @elseif($col === 'plate_number')
                            @if(in_array($col, $forceInput) || $routePrefix === 'cars')
                                <input type="text" id="{{ $col }}" name="{{ $col }}" required
                                    value="{{ old($col, $item->$col ?? '') }}"
                                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                            @else
                                <select id="{{ $col }}" name="{{ $col }}" required
                                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                    <option value="">-- Wybierz samochód --</option>
                                    @foreach($extraData['cars'] ?? [] as $plate => $label)
                                        <option value="{{ $plate }}" {{ old($col, $item->$col ?? '') == $plate ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                        @elseif($col === 'status')
                            <select id="{{ $col }}" name="{{ $col }}" required
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                <option value="">-- Wybierz status --</option>
                                @foreach($extraData['statuses'] ?? [] as $status)
                                    <option value="{{ $status }}" {{ old($col, $item->$col ?? '') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>

                        @elseif($col === 'method')
                            <select id="{{ $col }}" name="{{ $col }}" required
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">
                                <option value="">-- Wybierz metodę --</option>
                                @foreach($extraData['methods'] ?? [] as $method)
                                    <option value="{{ $method }}" {{ old($col, $item->$col ?? '') == $method ? 'selected' : '' }}>
                                        {{ ucfirst($method) }}
                                    </option>
                                @endforeach
                            </select>

                        @elseif(in_array($col, ['pickup_time', 'return_time']))
                            <input type="datetime-local" id="{{ $col }}" name="{{ $col }}"
                                value="{{ old($col, isset($item->$col) && $item->$col ? \Carbon\Carbon::parse($item->$col)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror"
                                {{ $col === 'pickup_time' ? 'required' : '' }}>

                        @elseif(in_array($col, ['start_time', 'end_time']))
                            <input type="datetime-local" id="{{ $col }}" name="{{ $col }}"
                                value="{{ old($col, isset($item->$col) && $item->$col ? \Carbon\Carbon::parse($item->$col)->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror"
                                {{ $col === 'start_time' ? 'required' : '' }}>

                        @elseif($col === 'distance_km')
                            <input type="number" id="{{ $col }}" name="{{ $col }}" min="0" step="1"
                                value="{{ old($col, $item->$col ?? '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">

                        @elseif($col === 'cost')
                            <input type="number" id="{{ $col }}" name="{{ $col }}" min="0" step="0.01"
                                value="{{ old($col, $item->$col ?? '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror">

                        @else
                            <input type="text" id="{{ $col }}" name="{{ $col }}"
                                value="{{ old($col, $item->$col ?? '') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white @error($col) border-red-600 @enderror"
                                {{ ($mode === 'create' && $col === 'rental_id') ? 'readonly' : '' }}>
                        @endif

                        @error($col)
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Zapisz
                </button>
            </form>
        </div>
    </div>
</x-app-layout>