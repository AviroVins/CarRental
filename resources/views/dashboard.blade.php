@extends('layouts.admin')

@section('content')
    <div class="alert alert-success" role="alert">
        Zalogowano!
    </div>

    <div class="container-fluid">
        <div class="row g-4">

            {{-- Lewy górny róg: Bob :) --}}
            <div class="col-md-6">
                <div class="card shadow h-100 d-flex flex-column align-items-center justify-content-center">
                    <div class="card-header w-100">Obrazek</div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                        <img src="{{ asset('images/raccoon.png') }}" alt="Raccoon" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                </div>
            </div>

            {{-- Prawy górny róg: kalendarz --}}
            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-header">Twój kalendarz wynajmów</div>
                    <div class="card-body" style="height: 300px;">
                        <div id="rentalCalendar" style="height: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Lewy dolny róg: Najczęściej wynajmowane pojazdy --}}
            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-header">Najczęściej wynajmowane pojazdy</div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="pastChart" style="max-height: 100%; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            {{-- Prawy dolny róg: Planowane / bieżące rezerwacje --}}
            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-header">Planowane / bieżące rezerwacje</div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="futureChart" style="max-height: 100%; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <style>
        .fc-daygrid-day.rental-day {
            background-color: #4821d1 !important;
        }rgb(83, 106, 181)rgb(61, 99, 222)
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { color: '#3f3f40' }
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
                datasets: [{ data: pastData, backgroundColor: pastColors }]
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
                datasets: [{ data: futureData, backgroundColor: futureColors }]
            },
            options: commonOptions
        });

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('rentalCalendar');
            const rentalDays = @json($rentalDays ?? []);

            const rentalEvents = rentalDays.map(day => ({
                start: day,
                display: 'background',
                backgroundColor: '#53b553'
            }));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: '100%',
                events: rentalEvents
            });

            calendar.render();
        });

    </script>
@endpush
