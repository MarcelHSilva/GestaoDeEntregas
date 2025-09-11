@extends('layouts.app')

@section('title', 'Relatório Semanal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-week"></i> Relatório Semanal</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Período:</strong> {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-truck" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $deliveries->sum('delivery_count') }}</h3>
                <p class="mb-0">Entregas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                <p class="mb-0">Receita Bruta</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-fuel-pump" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalFuelCost, 2, ',', '.') }}</h3>
                <p class="mb-0">Combustível</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="bi bi-tools" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalMaintenanceCost, 2, ',', '.') }}</h3>
                <p class="mb-0">Manutenções</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                <h3 class="mt-2">R$ {{ number_format($totalProfit, 2, ',', '.') }}</h3>
                <p class="mb-0">Lucro Líquido</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Entregas da Semana</h5>
            </div>
            <div class="card-body">
                @if($deliveries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Entregas</th>
                                    <th>KM</th>
                                    <th>Combustível</th>
                                    <th>Bruto</th>
                                    <th>Manutenção</th>
                                    <th>Líquido</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $processedDates = [];
                                @endphp
                                @foreach($deliveries as $delivery)
                                @php
                                    $dateKey = $delivery->date->format('Y-m-d');
                                    $deliveriesForDay = $deliveries->filter(function($d) use ($dateKey) {
                                        return $d->date->format('Y-m-d') === $dateKey;
                                    });
                                    $maintenanceCostForDay = isset($maintenancesByDay[$dateKey]) ? $maintenancesByDay[$dateKey]->sum('cost') : 0;
                                    $maintenanceCostPerDelivery = $deliveriesForDay->count() > 0 ? $maintenanceCostForDay / $deliveriesForDay->count() : 0;
                                    // Usar o valor net já calculado e salvo no banco de dados
                                    $adjustedNet = $delivery->net;
                                @endphp
                                <tr>
                                    <td>{{ $delivery->date->format('d/m') }}</td>
                                    <td>{{ $delivery->delivery_count }}</td>
                                    <td>{{ number_format($delivery->km_end - $delivery->km_start, 0, ',', '.') }}</td>
                                    <td>R$ {{ number_format($delivery->fuel_total, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($delivery->gross, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($maintenanceCostPerDelivery, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($adjustedNet, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Nenhuma entrega registrada nesta semana.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Manutenções da Semana</h5>
            </div>
            <div class="card-body">
                @if($maintenances->count() > 0)
                    @foreach($maintenances as $maintenance)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ $maintenance->date->format('d/m/Y') }}</small>
                            <strong class="text-danger">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</strong>
                        </div>
                        <p class="mb-0 small">{{ Str::limit($maintenance->description, 40) }}</p>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Nenhuma manutenção registrada nesta semana.</p>
                @endif
            </div>
        </div>

        @if($deliveries->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>KM Total:</span>
                    <span>{{ number_format($totalKm, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Combustível Total:</span>
                    <span>{{ number_format($totalFuel, 2, ',', '.') }}L</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Consumo Médio:</span>
                    <span>{{ number_format($totalKm / $totalFuel, 2, ',', '.') }} km/l</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Média por Entrega:</span>
                    <span>R$ {{ number_format($totalRevenue / $deliveries->sum('delivery_count'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection