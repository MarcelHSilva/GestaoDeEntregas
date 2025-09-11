@extends('layouts.app')

@section('title', 'Editar Manutenção')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil"></i> Editar Manutenção</h1>
    <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Data *</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                           id="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($maintenance->date)->format('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="km" class="form-label">KM (opcional)</label>
                    <input type="number" class="form-control @error('km') is-invalid @enderror" 
                           id="km" name="km" value="{{ old('km', $maintenance->km) }}" 
                           step="0.01" min="0">
                    @error('km')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description', $maintenance->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cost" class="form-label">Custo *</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" class="form-control @error('cost') is-invalid @enderror" 
                               id="cost" name="cost" value="{{ old('cost', $maintenance->cost) }}" 
                               step="0.01" min="0" required>
                    </div>
                    @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection