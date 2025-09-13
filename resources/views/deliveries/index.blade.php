@extends('layouts.app')

@section('title', 'Entregas')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1><i class="bi bi-truck"></i> Entregas</h1>
        <p class="text-muted mb-0">Gerencie suas entregas diárias</p>
    </div>
    <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nova Entrega
    </a>
</div>

<!-- Statistics Cards -->
@if($recentDeliveries->count() > 0)
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon bg-primary">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $recentDeliveries->sum('delivery_count') }}</div>
            <div class="stat-label">Entregas (30 dias)</div>
            <div class="stat-change positive">
                <i class="bi bi-arrow-up"></i> +12% este mês
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-success">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">R$ {{ number_format($recentDeliveries->sum('gross'), 2, ',', '.') }}</div>
            <div class="stat-label">Receita (30 dias)</div>
            <div class="stat-change positive">
                <i class="bi bi-arrow-up"></i> +8% este mês
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-warning">
            <i class="bi bi-fuel-pump"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">R$ {{ number_format($recentDeliveries->sum('fuel_total'), 2, ',', '.') }}</div>
            <div class="stat-label">Combustível (30 dias)</div>
            <div class="stat-change negative">
                <i class="bi bi-arrow-down"></i> -3% este mês
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-info">
            <i class="bi bi-graph-up"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">R$ {{ number_format($recentDeliveries->sum('net'), 2, ',', '.') }}</div>
            <div class="stat-label">Lucro (30 dias)</div>
            <div class="stat-change {{ $recentDeliveries->sum('net') > 0 ? 'positive' : 'negative' }}">
                <i class="bi bi-arrow-{{ $recentDeliveries->sum('net') > 0 ? 'up' : 'down' }}"></i>
                {{ $recentDeliveries->sum('net') > 0 ? '+15%' : '-5%' }} este mês
            </div>
        </div>
    </div>
</div>
@endif

@if($deliveries->count() > 0)
<!-- Deliveries Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Lista de Entregas</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar3 me-1"></i>Data</th>
                        <th><i class="bi bi-speedometer me-1"></i>KM</th>
                        <th><i class="bi bi-box-seam me-1"></i>Entregas</th>
                        <th><i class="bi bi-currency-dollar me-1"></i>Valor/Un</th>
                        <th><i class="bi bi-fuel-pump me-1"></i>Combustível</th>
                        <th><i class="bi bi-cash-stack me-1"></i>Bruto</th>
                        <th><i class="bi bi-tools me-1"></i>Manutenção</th>
                        <th><i class="bi bi-graph-up me-1"></i>Líquido</th>
                        <th class="text-center"><i class="bi bi-gear me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    @php
                        $adjustedNet = $delivery->net;
                    @endphp
                    <tr class="{{ $adjustedNet > 0 ? 'table-success' : ($adjustedNet < 0 ? 'table-danger' : '') }}">
                        <td>
                            <div class="fw-bold">{{ $delivery->date->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $delivery->date->format('D') }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ number_format($delivery->km_end - $delivery->km_start, 0, ',', '.') }} km</span>
                                <small class="text-muted">{{ number_format($delivery->km_start, 0, ',', '.') }} → {{ number_format($delivery->km_end, 0, ',', '.') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill">{{ $delivery->delivery_count }}</span>
                        </td>
                        <td>
                            <span class="fw-bold">R$ {{ number_format($delivery->price_per_delivery, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            @if($delivery->fuel_total)
                                <div class="d-flex flex-column">
                                    <span class="fw-bold value-negative">R$ {{ number_format($delivery->fuel_total, 2, ',', '.') }}</span>
                                    <small class="text-muted">{{ number_format($delivery->liters, 1, ',', '.') }}L • {{ number_format($delivery->km_per_liter, 1, ',', '.') }} km/l</small>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold value-positive">R$ {{ number_format($delivery->gross, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            @php
                                $dayKey = $delivery->date->format('Y-m-d');
                                $dayMaintenanceCost = isset($maintenancesByDay[$dayKey]) ? $maintenancesByDay[$dayKey]->sum('cost') : 0;
                                $deliveriesOnDay = \App\Models\Delivery::whereRaw('DATE(date) = ?', [$delivery->date->format('Y-m-d')])->count();
                                $maintenanceCostPerDelivery = $deliveriesOnDay > 0 ? $dayMaintenanceCost / $deliveriesOnDay : 0;
                            @endphp
                            @if($maintenanceCostPerDelivery > 0)
                                <span class="fw-bold value-negative">R$ {{ number_format($maintenanceCostPerDelivery, 2, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold {{ $adjustedNet > 0 ? 'value-positive' : 'value-negative' }}">R$ {{ number_format($adjustedNet, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-info" title="Visualizar">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('deliveries.destroy', $delivery) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta entrega?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div class="d-flex justify-content-center mt-4">
        {{ $deliveries->links('custom-pagination') }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center">
            <i class="bi bi-truck" style="font-size: 3rem; color: #6c757d;"></i>
            <h4 class="mt-3">Nenhuma entrega cadastrada</h4>
            <p class="text-muted">Comece adicionando sua primeira entrega.</p>
            <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nova Entrega
            </a>
        </div>
    </div>
@endif
@endsection
