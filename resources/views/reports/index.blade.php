@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-graph-up"></i> Relatórios</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-week" style="font-size: 3rem; color: #0d6efd;"></i>
                <h4 class="mt-3">Relatório Semanal</h4>
                <p class="text-muted">Visualize os dados da semana atual com resumo de entregas, manutenções e lucros.</p>
                <a href="{{ route('reports.weekly') }}" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Ver Relatório Semanal
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-month" style="font-size: 3rem; color: #198754;"></i>
                <h4 class="mt-3">Relatório Mensal</h4>
                <p class="text-muted">Visualize os dados do mês atual com análise detalhada de performance e custos.</p>
                <a href="{{ route('reports.monthly') }}" class="btn btn-success">
                    <i class="bi bi-eye"></i> Ver Relatório Mensal
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-range" style="font-size: 3rem; color: #fd7e14;"></i>
                <h4 class="mt-3">Relatório Personalizado</h4>
                <p class="text-muted">Escolha o período desejado e visualize dados personalizados com filtros de data.</p>
                <a href="{{ route('reports.custom') }}" class="btn btn-warning">
                    <i class="bi bi-funnel"></i> Ver Relatório Personalizado
                </a>
            </div>
        </div>
    </div>
</div>


@endsection