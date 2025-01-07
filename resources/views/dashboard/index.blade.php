@extends('layouts.default')
@section('content')
<canvas id="roundedLineChart" width="400" height="200"></canvas>

<script>
    async function fetchChartData() {
            const response = await fetch('/chart-data');
            const data = await response.json();

            const ctx = document.getElementById('roundedLineChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: data.datasets // Load datasets dynamically from JSON
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        fetchChartData();
</script>
@stop