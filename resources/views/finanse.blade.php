@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Finanse i statystyki wynajmu</h2>

        {{-- Rząd 1: Zarobki (cała szerokość) --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow h-100">
                    <div class="card-header">Zarobki z wynajmu (ostatnie 30 dni)</div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="earningsChart" style="max-height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rząd 2: Popularność i Status płatności --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header">Popularność wynajmu (ilość wypożyczeń – ostatnie 30 dni)</div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="popularityChart" style="max-height: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header">Status płatności</div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="paymentsChart" style="max-height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const days = @json($days);
        const earnings = @json($earnings);
        const paidCount = @json($paidCount);
        const pendingCount = @json($pendingCount);
        const popularity = @json($popularity);

        // Zarobki
        new Chart(document.getElementById('earningsChart'), {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: 'Zarobki (PLN)',
                    data: earnings,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,128,0,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Status płatności
        new Chart(document.getElementById('paymentsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Opłacone', 'Oczekujące'],
                datasets: [{
                    data: [paidCount, pendingCount],
                    backgroundColor: ['#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Popularność wynajmu
        new Chart(document.getElementById('popularityChart'), {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Wypożyczenia',
                    data: popularity,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, stepSize: 1 }
                }
            }
        });
    </script>
@endpush
