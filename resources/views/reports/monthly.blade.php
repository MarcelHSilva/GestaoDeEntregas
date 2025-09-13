@extends('layouts.app')

@section('title', 'Relatório Mensal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-month"></i> Relatório Mensal</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Período:</strong> {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }} ({{ $startDate->format('F Y') }})
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-truck" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">{{ $deliveries->sum('delivery_count') }}</h4>
                <p class="mb-0 small">Entregas</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h4>
                <p class="mb-0 small">Receita Bruta</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-fuel-pump" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">R$ {{ number_format($totalFuelCost, 2, ',', '.') }}</h4>
                <p class="mb-0 small">Combustível</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="bi bi-tools" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">R$ {{ number_format($totalMaintenanceCost, 2, ',', '.') }}</h4>
                <p class="mb-0 small">Manutenções</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="bi bi-graph-up" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">R$ {{ number_format($totalProfit, 2, ',', '.') }}</h4>
                <p class="mb-0 small">Lucro Líquido</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="bi bi-speedometer2" style="font-size: 1.5rem;"></i>
                <h4 class="mt-2">{{ number_format($totalKm, 0, ',', '.') }}</h4>
                <p class="mb-0 small">KM Total</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Entregas por Dia</h5>
            </div>
            <div class="card-body">
                @if($deliveries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-calendar3 me-1"></i>Data</th>
                                    <th><i class="bi bi-box-seam me-1"></i>Entregas</th>
                                    <th><i class="bi bi-speedometer me-1"></i>KM</th>
                                    <th><i class="bi bi-fuel-pump me-1"></i>Combustível</th>
                                    <th><i class="bi bi-cash-stack me-1"></i>Bruto</th>
                                    <th><i class="bi bi-tools me-1"></i>Manutenção</th>
                                    <th><i class="bi bi-graph-up me-1"></i>Líquido</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveriesByDay as $day => $dayDeliveries)
                                @php
                                    $dayKm = $dayDeliveries->sum(function($d) { return $d->km_end - $d->km_start; });
                                    $dayFuel = $dayDeliveries->sum('fuel_total');
                                    $dayGross = $dayDeliveries->sum('gross');
                                    $dayNet = $dayDeliveries->sum('net');
                                    $dayMaintenanceCost = isset($maintenancesByDay[$day]) ? $maintenancesByDay[$day]->sum('cost') : 0;
                                    $deliveriesCount = $dayDeliveries->sum('delivery_count');
                                    $maintenanceCostPerDelivery = $deliveriesCount > 0 ? $dayMaintenanceCost / $deliveriesCount : 0;
                                    $adjustedNet = $dayNet;
                                @endphp
                                <tr class="{{ $adjustedNet > 0 ? 'table-success' : ($adjustedNet < 0 ? 'table-danger' : '') }}">
                                    <td>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($day)->format('D') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">{{ $dayDeliveries->sum('delivery_count') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($dayKm, 0, ',', '.') }} km</span>
                                    </td>
                                    <td>
                                        @if($dayFuel > 0)
                                            <span class="fw-bold value-negative">R$ {{ number_format($dayFuel, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold value-positive">R$ {{ number_format($dayGross, 2, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if($dayMaintenanceCost > 0)
                                            <span class="fw-bold value-negative">R$ {{ number_format($dayMaintenanceCost, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $adjustedNet > 0 ? 'value-positive' : 'value-negative' }}">R$ {{ number_format($adjustedNet, 2, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Nenhuma entrega registrada neste mês.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas do Mês</h5>
            </div>
            <div class="card-body">
                @if($deliveries->count() > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Média por Dia:</span>
                        <span>R$ {{ number_format($totalRevenue / $startDate->daysInMonth, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Média por Entrega:</span>
                        <span>R$ {{ number_format($totalRevenue / $deliveries->sum('delivery_count'), 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Consumo Médio:</span>
                        <span>{{ number_format($totalKm / $totalFuel, 2, ',', '.') }} km/l</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Custo por KM:</span>
                        <span>R$ {{ number_format(($totalFuelCost + $totalMaintenanceCost) / $totalKm, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Lucro por KM:</span>
                        <span>R$ {{ number_format($totalProfit / $totalKm, 2, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Margem de Lucro:</span>
                        <span>{{ number_format(($totalProfit / $totalRevenue) * 100, 1, ',', '.') }}%</span>
                    </div>
                @else
                    <p class="text-muted">Sem dados para calcular estatísticas.</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Manutenções do Mês</h5>
            </div>
            <div class="card-body">
                @if($maintenances->count() > 0)
                    <div style="max-height: 300px; overflow-y: auto;">
                        @foreach($maintenances as $maintenance)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ $maintenance->date->format('d/m') }}</small>
                                <strong class="text-danger">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</strong>
                            </div>
                            <p class="mb-0 small">{{ Str::limit($maintenance->description, 35) }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Nenhuma manutenção registrada neste mês.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection