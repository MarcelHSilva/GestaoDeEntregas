@extends('layouts.app')

@section('title', 'Relatório Personalizado')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-range"></i> Relatório Personalizado</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<!-- Formulário de Filtro de Datas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtrar por Período</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.custom') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Data Inicial</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Data Final</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('reports.custom') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Período:</strong> {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}
            ({{ round($startDate->diffInDays($endDate)) + 1 }} dias)
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-truck" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $deliveries->sum('delivery_count') }}</h3>
                <p class="mb-0">Entregas</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                <p class="mb-0">Receita Bruta</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-fuel-pump" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalFuelCost, 2, ',', '.') }}</h3>
                <p class="mb-0">Combustível</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="bi bi-tools" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalMaintenanceCost, 2, ',', '.') }}</h3>
                <p class="mb-0">Manutenções</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalProfit, 2, ',', '.') }}</h3>
                <p class="mb-0">Lucro Líquido</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="bi bi-speedometer2" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ number_format($totalKm, 0, ',', '.') }}</h3>
                <p class="mb-0">KM Total</p>
            </div>
        </div>
    </div>
</div>

@if($deliveries->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Entregas por Dia</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Entregas</th>
                                <th>KM</th>
                                <th>Combustível</th>
                                <th>Receita Bruta</th>
                                <th>Manutenção</th>
                                <th>Receita Líquida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveriesByDay as $date => $dayDeliveries)
                            @php
                                $dayKm = $dayDeliveries->sum(function($delivery) {
                                    return $delivery->km_end - $delivery->km_start;
                                });
                                $dayFuel = $dayDeliveries->sum('fuel_total');
                                $dayGross = $dayDeliveries->sum('gross');
                                $dayNet = $dayDeliveries->sum('net');
                                $dayMaintenanceCost = isset($maintenancesByDay[$date]) ? $maintenancesByDay[$date]->sum('cost') : 0;
                                $adjustedNet = $dayGross - $dayFuel - $dayMaintenanceCost;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                <td>{{ $dayDeliveries->sum('delivery_count') }}</td>
                                <td>{{ number_format($dayKm, 0, ',', '.') }}</td>
                                <td>R$ {{ number_format($dayFuel, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($dayGross, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($dayMaintenanceCost, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($adjustedNet, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas do Período</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Média por Dia:</span>
                    <span>R$ {{ number_format($totalRevenue / max(1, $startDate->diffInDays($endDate) + 1), 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Média por Entrega:</span>
                    <span>R$ {{ $deliveries->sum('delivery_count') > 0 ? number_format($totalRevenue / $deliveries->sum('delivery_count'), 2, ',', '.') : '0,00' }}</span>
                </div>
                @if($totalFuel > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Consumo Médio:</span>
                    <span>{{ number_format($totalKm / $totalFuel, 2, ',', '.') }} km/l</span>
                </div>
                @endif
                @if($totalKm > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Custo por KM:</span>
                    <span>R$ {{ number_format(($totalFuelCost + $totalMaintenanceCost) / $totalKm, 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Lucro por KM:</span>
                    <span>R$ {{ number_format($totalProfit / $totalKm, 2, ',', '.') }}</span>
                </div>
                @endif
                @if($totalRevenue > 0)
                <div class="d-flex justify-content-between">
                    <span>Margem de Lucro:</span>
                    <span>{{ number_format(($totalProfit / $totalRevenue) * 100, 1, ',', '.') }}%</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Manutenções do Período</h5>
            </div>
            <div class="card-body">
                @if($maintenances->count() > 0)
                    @foreach($maintenances as $maintenance)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ $maintenance->date->format('d/m/Y') }}</small>
                            <strong class="text-danger">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</strong>
                        </div>
                        <p class="mb-0 small">{{ Str::limit($maintenance->description, 50) }}</p>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Nenhuma manutenção registrada neste período.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
            <h4 class="mt-2">Nenhuma entrega encontrada</h4>
            <p class="mb-0">Não há entregas registradas no período selecionado.</p>
        </div>
    </div>
</div>
@endif
@endsection