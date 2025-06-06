<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Zalogowano!") }}

                    <div class="mt-4">
                        <a href="{{ route('user.reservations.index') }}"
                           class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                            Twoje rezerwacje
                        </a>
                    </div>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <div class="mt-4">
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Panel Administratora
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- WYKRESY -->
    <div class="flex flex-row gap-6 max-w-7xl mx-auto px-4 lg:px-8">
        <!-- Wykres rentals -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
            <h3 class="text-center font-semibold text-gray-700 dark:text-gray-200 mb-4">
                Najczęściej wynajmowane pojazdy (rentals)
            </h3>
            <canvas id="pastChart" style="max-width: 300px; max-height: 300px; width: 100%; color: #00000;"></canvas>
        </div>

        <!-- Wykres reservations -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
            <h3 class="text-center font-semibold text-gray-700 dark:text-gray-200 mb-4">
                Planowane / bieżące rezerwacje (reservations)
            </h3>
            <canvas id="futureChart" style="max-width: 300px; max-height: 300px; width: 100%; color: #00000;"></canvas>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart.js -->
<script>
    const commonOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: '#ffffff' // biały kolor legendy
                }
            }
        }
    };

    // Funkcja do generowania kolorów HEX
    function getRandomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
    }

    // Wygeneruj kolory dla przeszłych wynajmów
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

    // Wygeneruj kolory dla przyszłych rezerwacji
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