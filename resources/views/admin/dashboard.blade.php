@extends('layouts.admin')

@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">Dashboard</h3>

    <!-- Cards de informações -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Produtos</h5>
                    <h3 class="card-text">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Categorias</h5>
                    <h3 class="card-text">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Pedidos</h5>
                    <h3 class="card-text">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h5 class="text-center">Pedidos por Categoria</h5>
                <canvas id="ordersByCategoryChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h5 class="text-center">Faturamento Mensal</h5>
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Gráfico de Pedidos por Categoria
    let ordersByCategoryCtx = document.getElementById('ordersByCategoryChart').getContext('2d');
    let ordersByCategoryChart = new Chart(ordersByCategoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ordersByCategory->pluck('name')) !!},
            datasets: [{
                label: 'Pedidos',
                data: {!! json_encode($ordersByCategory->pluck('total')) !!},
                backgroundColor: '#4a011e',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Gráfico de Faturamento Mensal
    let monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    let monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Faturamento (R$)',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: '#ff4081',
                backgroundColor: 'rgba(255, 64, 129, 0.2)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection

