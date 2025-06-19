@extends('layouts.admin')

@section('content')
    <div class="alert alert-success" role="alert">
        Zalogowano!
    </div>

    <div class="mb-4">
        <a href="{{ route('user.reservations.index') }}" class="btn btn-success">
            Twoje rezerwacje
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    Najczęściej wynajmowane pojazdy
                </div>
                <div class="card-body text-center">
                    <canvas id="pastChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    Planowane / bieżące rezerwacje
                </div>
                <div class="card-body text-center">
                    <canvas id="futureChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const commonOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#3f3f40'
                    }
                }
            }
        };

        function getRandomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
        }

        const pastLabels = @json($pastLabels ?? []);
        const pastData = @json($pastData ?? []);
        const pastColors = pastLabels.map(() => getRandomColor());

        const ctxPast = document.getElementById('pastChart').getContext('2d');
        new Chart(ctxPast, {
            type: 'pie',
            data: {
                labels: pastLabels,
                datasets: [{
                    data: pastData,
                    backgroundColor: pastColors,
                }]
            },
            options: commonOptions
        });

        const futureLabels = @json($futureLabels ?? []);
        const futureData = @json($futureData ?? []);
        const futureColors = futureLabels.map(() => getRandomColor());

        const ctxFuture = document.getElementById('futureChart').getContext('2d');
        new Chart(ctxFuture, {
            type: 'pie',
            data: {
                labels: futureLabels,
                datasets: [{
                    data: futureData,
                    backgroundColor: futureColors,
                }]
            },
            options: commonOptions
        });
    </script>
@endpush
