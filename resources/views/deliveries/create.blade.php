@extends('layouts.app')

@section('title', 'Nova Entrega')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-plus-circle"></i> Nova Entrega</h1>
    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('deliveries.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Data *</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                           id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="km_start" class="form-label">KM Inicial</label>
                    <input type="number" class="form-control @error('km_start') is-invalid @enderror" 
                           id="km_start" name="km_start" value="{{ old('km_start', 0) }}" 
                           step="0.01" min="0" placeholder="0">
                    <div class="form-text">Deixe 0 para trip zerado diariamente</div>
                    @error('km_start')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="km_end" class="form-label">KM Final *</label>
                    <input type="number" class="form-control @error('km_end') is-invalid @enderror" 
                           id="km_end" name="km_end" value="{{ old('km_end') }}" 
                           step="0.01" min="0" required>
                    @error('km_end')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="delivery_count" class="form-label">Quantidade de Entregas *</label>
                    <input type="number" class="form-control @error('delivery_count') is-invalid @enderror" 
                           id="delivery_count" name="delivery_count" value="{{ old('delivery_count', 1) }}" 
                           min="1" required>
                    @error('delivery_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price_per_delivery" class="form-label">Valor por Entrega (R$) *</label>
                    <input type="number" class="form-control @error('price_per_delivery') is-invalid @enderror" 
                           id="price_per_delivery" name="price_per_delivery" value="{{ old('price_per_delivery', '4.50') }}" 
                           step="0.01" min="0" required>
                    @error('price_per_delivery')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="km_per_liter" class="form-label">KM por Litro</label>
                    <input type="number" class="form-control @error('km_per_liter') is-invalid @enderror" 
                           id="km_per_liter" name="km_per_liter" value="{{ old('km_per_liter') }}" 
                           step="0.01" min="0" placeholder="Ex: 30.5">
                    <div class="form-text">Quantos quilômetros sua moto faz por litro</div>
                    @error('km_per_liter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price_per_liter" class="form-label">Preço por Litro</label>
                    <input type="number" class="form-control @error('price_per_liter') is-invalid @enderror" 
                           id="price_per_liter" name="price_per_liter" value="{{ old('price_per_liter') }}" 
                           step="0.01" min="0" placeholder="Ex: 5.50">
                    <div class="form-text">Preço do combustível por litro</div>
                    @error('price_per_liter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info" role="alert">
                <strong>Cálculo Automático:</strong> Os litros consumidos serão calculados automaticamente baseado nos KM rodados dividido pelos KM por litro.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection