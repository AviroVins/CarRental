@extends('layouts.admin')

@section('content')

<h1 class="text-center text-primary fw-bold mb-5">
    <i class="fas fa-car-side me-2"></i>Wypożyczalnia aut - Strona główna
</h1>

<h2 class="text-center h4 text-secondary mb-4">
    <i class="fas fa-list-ul me-2"></i>Dostępne samochody
</h2>

@if(isset($cars) && $cars->count() > 0)
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($cars as $car)
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <img src="{{ asset('images/audi_q4.png') }}" class="card-img-top" alt="{{ $car->maker }} {{ $car->model }}">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $car->maker }} {{ $car->model }}</h5>
                        <p class="card-text mb-1">Rok produkcji: <span class="badge bg-info text-dark">{{ $car->year }}</span></p>
                    </div>
                    <div class="card-footer text-muted text-end">
                        <small><i class="fas fa-car"></i> Marka i model</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-center text-muted fst-italic">
        Brak dostępnych samochodów.
    </p>
@endif

@endsection
