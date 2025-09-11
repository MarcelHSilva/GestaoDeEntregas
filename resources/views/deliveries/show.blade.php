@extends('layouts.app')

@section('title', 'Detalhes da Entrega')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye"></i> Detalhes da Entrega</h1>
    <div>
        <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações da Entrega</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Data:</label>
                        <p class="form-control-plaintext">{{ $delivery->date->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">KM Inicial:</label>
                        <p class="form-control-plaintext">{{ number_format($delivery->km_start, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">KM Final:</label>
                        <p class="form-control-plaintext">{{ number_format($delivery->km_end, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">KM Rodados:</label>
                        <p class="form-control-plaintext">{{ number_format($delivery->km_end - $delivery->km_start, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Quantidade de Entregas:</label>
                        <p class="form-control-plaintext">{{ $delivery->delivery_count }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Valor por Entrega:</label>
                        <p class="form-control-plaintext">R$ {{ number_format($delivery->price_per_delivery, 2, ',', '.') }}</p>
                    </div>
                </div>

                <hr>

                @if($delivery->liters)
                <div class="row">
                    <div class="col-md-6">
                        <strong>Litros Consumidos:</strong>
                        <span class="text-info">{{ number_format($delivery->liters, 2, ',', '.') }}L</span>
                        @if($delivery->km_per_liter)
                            <small class="text-muted d-block">Calculado: {{ $delivery->km_end - $delivery->km_start }}km ÷ {{ number_format($delivery->km_per_liter, 1) }}km/l</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Custo do Combustível:</strong>
                        @if($delivery->fuel_total)
                            <span class="text-danger">R$ {{ number_format($delivery->fuel_total, 2, ',', '.') }}</span>
                            <small class="text-muted d-block">{{ number_format($delivery->liters, 2) }}L × R$ {{ number_format($delivery->price_per_liter, 2) }}</small>
                        @else
                            <span class="text-muted">R$ 0,00</span>
                        @endif
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Combustível:</strong> Não foi registrado abastecimento neste dia.
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Valor Bruto:</label>
                        <p class="form-control-plaintext">R$ {{ number_format($delivery->gross, 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Valor Líquido:</label>
                        <p class="form-control-plaintext text-success fw-bold">R$ {{ number_format($delivery->net, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resumo</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Consumo:</span>
                    <span>{{ number_format(($delivery->km_end - $delivery->km_start) / $delivery->liters, 2, ',', '.') }} km/l</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Custo por KM:</span>
                    <span>R$ {{ number_format($delivery->fuel_total / ($delivery->km_end - $delivery->km_start), 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Lucro por KM:</span>
                    <span>R$ {{ number_format($delivery->net / ($delivery->km_end - $delivery->km_start), 2, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Margem de Lucro:</span>
                    <span>{{ number_format(($delivery->net / $delivery->gross) * 100, 1, ',', '.') }}%</span>
                </div>
                
                @if($delivery->maintenances && $delivery->maintenances->count() > 0)
                    <hr>
                    <div class="d-flex justify-content-between text-warning">
                        <span>Custo Manutenções:</span>
                        <span>- R$ {{ number_format($delivery->maintenances->sum('cost'), 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold text-success">
                        <span>Lucro Real:</span>
                        <span>R$ {{ number_format($delivery->net - $delivery->maintenances->sum('cost'), 2, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Seção de Manutenções -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-tools"></i> Manutenções do Dia</h5>
            </div>
            <div class="card-body">
                @if($delivery->maintenances && $delivery->maintenances->count() > 0)
                    @foreach($delivery->maintenances as $maintenance)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Data:</strong><br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($maintenance->date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>KM:</strong><br>
                                    <span class="text-muted">{{ $maintenance->km ? number_format($maintenance->km, 0, ',', '.') : '0' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Custo:</strong><br>
                                    <span class="text-danger">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Descrição:</strong><br>
                                    <span class="text-muted">{{ $maintenance->description ?: 'Sem descrição' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Total de Manutenções -->
                    <div class="alert alert-warning mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="bi bi-exclamation-triangle"></i> Total de Manutenções:</strong>
                            </div>
                            <div class="col-md-6 text-end">
                                <strong class="text-danger">R$ {{ number_format($delivery->maintenances->sum('cost'), 2, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        <strong>Nenhuma manutenção registrada neste dia.</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection