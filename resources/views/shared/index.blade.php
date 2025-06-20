@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($routePrefix !== 'user.payments')
    <a href="{{ route($routePrefix . '.create') }}" class="btn btn-success mb-4">Dodaj nowy</a>
@endif

{{-- Formularz filtrowania --}}
@if ($routePrefix === 'payments')
    <form method="GET" action="{{ route('payments.index') }}" class="mb-4 row g-2">
        <div class="col-md-4">
            <label for="status" class="form-label">Status płatności</label>
            <select name="status" id="status" class="form-control">
                <option value="">-- Wszystkie --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Oczekuje na zapłatę</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Opłacone</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="method" class="form-label">Metoda płatności</label>
            <select name="method" id="method" class="form-control">
                <option value="">-- Wszystkie --</option>
                <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Karta</option>
                <option value="blik" {{ request('method') === 'blik' ? 'selected' : '' }}>Blik</option>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filtruj</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Wyczyść</a>
        </div>
    </form>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th>
                                {{-- Użytkownik-Płatności --}}
                                @if($col === 'rental_id' && $routePrefix === 'user.payments')
                                    Samochód
                                @elseif($col === 'payment_id' && $routePrefix === 'user.payments')
                                    Identyfikator Płatności
                                @elseif($col === 'amount' && $routePrefix === 'user.payments')
                                    Kwota [PLN]
                                @elseif($col === 'method' && $routePrefix === 'user.payments')
                                    Metoda Płatności

                                {{-- Użytkownik-Rezerwacje --}}
                                @elseif($col === 'reservation_id' && $routePrefix === 'user.reservations')
                                    Identyfikator Rezerwacji
                                @elseif($col === 'plate_number' && $routePrefix === 'user.reservations')
                                    Samochód
                                @elseif($col === 'start_time' && $routePrefix === 'user.reservations')
                                    Start
                                @elseif($col === 'end_time' && $routePrefix === 'user.reservations')
                                    Zakończenie

                                {{-- Kolumny dla pozostałych przypadków --}}
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $col)) }}
                                @endif
                            </th>
                        @endforeach
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            @foreach($columns as $col)
                                <td>
                                    {{--- Tłumaczenie dla wyglądu :> ---}}

                                    {{-- Użytkownik-Płatności  oraz Płatności --}}
                                    @if ($col === 'rental_id' && $routePrefix === 'user.payments')
                                        <strong>{{ $item->rental && $item->rental->car ? $item->rental->car->make . ' ' . $item->rental->car->model . ' - [' . $item->rental->car->plate_number . ']' : 'Brak danych' }}</strong>

                                    @elseif ($col === 'status' && ($routePrefix === 'user.payments' || $routePrefix === 'payments'))
                                        @if ($item->status === 'paid')
                                            <span class="text-success fw-bold fs-"><strong>Opłacone</strong></span>
                                        @elseif ($item->status === 'pending')
                                            <span class="text-primary fw-bold"><strong>Oczekuje na zapłatę</strong></span>
                                        @else
                                            {{ $item->status }}
                                        @endif

                                    @elseif ($col === 'method' && $routePrefix === 'user.payments')
                                        @if ($item->method === 'card')
                                            <strong>Karta</strong>
                                        @elseif ($item->method === 'blik')
                                            <strong>Blik</strong>
                                        @else
                                            <strong>{{ $item->method }}</strong>
                                        @endif

                                    {{-- Użytkownik-Rezerwacje oraz Rezerwacje --}}
                                    @elseif ($col === 'plate_number' && $routePrefix === 'user.reservations')
                                        <strong>{{ $item->car ? $item->car->make . ' ' . $item->car->model . ' - [' . $item->car->plate_number . ']' : 'Brak danych' }}</strong>

                                    @elseif ($col === 'status' && ($routePrefix === 'user.reservations' || $routePrefix === 'reservations'))
                                        @if ($item->status === 'reserved')
                                            <span class="text-primary fw-bold"><strong>Zarezerwowany</strong></span>
                                        @elseif ($item->status === 'in_progress')
                                            <span class="text-warning fw-bold"><strong>Realizowany</strong></span>
                                        @elseif ($item->status === 'completed')
                                            <span class="text-success fw-bold"><strong>Zakończony</strong></span>
                                        @else
                                            {{ $item->status }}
                                        @endif

                                    {{-- Samochody --}}
                                    @elseif ($col === 'status' && $routePrefix === 'cars')
                                        @if ($item->status === 'available')
                                            <span class="text-success fw-bold"><strong>Dostępny</strong></span>
                                        @elseif ($item->status === 'maintenance')
                                            <span class="text-warning fw-bold"><strong>Warsztat</strong></span>
                                        @elseif ($item->status === 'rented')
                                            <span class="text-danger fw-bold"><strong>Wynajęty</strong></span>
                                        @else
                                            {{ $item->status }}
                                        @endif

                                    {{-- Dane dla pozostałych przypadków --}}
                                    @else
                                        <strong>{{ $item->$col }}</strong>
                                    @endif
                                </td>
                            @endforeach

                            <td>
                                @if ($routePrefix === 'user.payments')
                                    @if ($item->status === 'pending')
                                        <form action="{{ route('user.payments.pay', $item) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-success btn-sm">Zapłać</button>
                                        </form>
                                        <form action="{{ route('user.payments.changePayment', $item) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-primary btn-sm">Zmień metodę płatności</button>
                                        </form>
                                    @else
                                        <p>Płatność zakończona</p>
                                    @endif

                                @elseif ($routePrefix === 'user.reservations')
                                    @if ($item->status === 'reserved')
                                        <a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-primary btn-sm">Edytuj</a>
                                        <form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Na pewno chcesz usunąć?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Zrezygnuj</button>
                                        </form>
                                    @elseif ($item->status === 'in_progress')
                                        <form action="{{ route('user.reservations.finish', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Zakończyć rezerwację?')">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-success btn-sm">Zakończ</button>
                                        </form>
                                    @else
                                        <p>Wynajem zakończony</p>
                                    @endif

                                @else
                                    <a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-primary btn-sm">Edytuj</a>
                                    <form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Na pewno chcesz usunąć?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Usuń</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Paginacja --}}
            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
