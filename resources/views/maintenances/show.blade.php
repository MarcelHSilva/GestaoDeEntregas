@extends('layouts.app')

@section('title', 'Detalhes da Manutenção')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye"></i> Detalhes da Manutenção</h1>
    <div>
        <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Informações da Manutenção</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Data:</label>
                <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($maintenance->date)->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">KM:</label>
                <p class="form-control-plaintext">{{ $maintenance->km ? number_format($maintenance->km, 0, ',', '.') : 'Não informado' }}</p>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Descrição:</label>
            <div class="border rounded p-3 bg-light">
                {{ $maintenance->description }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Custo:</label>
                <p class="form-control-plaintext text-danger fw-bold fs-5">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Cadastrado em:</label>
                <p class="form-control-plaintext text-muted">{{ \Carbon\Carbon::parse($maintenance->created_at)->format('d/m/Y H:i') }}</p>
            </div>
            @if($maintenance->updated_at != $maintenance->created_at)
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Última atualização:</label>
                <p class="form-control-plaintext text-muted">{{ \Carbon\Carbon::parse($maintenance->updated_at)->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection