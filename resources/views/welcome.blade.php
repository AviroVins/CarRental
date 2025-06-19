@extends('layouts.admin')

@section('content')

    <h1 class="text-center text-primary fw-bold mb-4">
        <i class="fas fa-car-side me-2"></i>Wypożyczalnia aut - Strona główna
    </h1>

    <h2 class="text-center h4 text-secondary mb-4">
        <i class="fas fa-list-ul me-2"></i>Dostępne samochody
    </h2>

    @if(isset($cars) && $cars->count() > 0)
        <div class="card shadow mb-4 border-primary">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle text-dark mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Marka</th>
                                <th>Model</th>
                                <th>Rok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cars as $car)
                                <tr class="align-middle">
                                    <td class="fw-semibold">{{ $car->maker }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $car->year }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p class="text-center text-muted fst-italic">
            Brak dostępnych samochodów.
        </p>
    @endif

@endsection
