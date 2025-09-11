@extends('layouts.app')

@section('title', 'Manutenções')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-tools"></i> Manutenções</h1>
    <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Manutenção
    </a>
</div>

@if($maintenances->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Custo</th>
                            <th>KM</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintenances as $maintenance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($maintenance->date)->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($maintenance->description, 50) }}</td>
                            <td>R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</td>
                            <td>{{ $maintenance->km ? number_format($maintenance->km, 0, ',', '.') : '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta manutenção?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
        {{ $maintenances->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center">
            <i class="bi bi-tools" style="font-size: 3rem; color: #6c757d;"></i>
            <h4 class="mt-3">Nenhuma manutenção cadastrada</h4>
            <p class="text-muted">Comece adicionando sua primeira manutenção.</p>
            <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nova Manutenção
            </a>
        </div>
    </div>
@endif
@endsection