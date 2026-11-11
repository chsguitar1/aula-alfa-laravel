@extends('layouts.default')

@section('content')
<div class="row mt-4">
    <!-- Bloco 1 -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary shadow-sm">
            <div class="inner">
                <h3>150</h3>
                <p>Novos Pedidos</p>
            </div>
            <div class="small-box-icon"><i class="bi bi-cart"></i></div>
        </div>
    </div>
    <!-- Bloco 2 -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success shadow-sm">
            <div class="inner">
                <h3>53<sup class="fs-5">%</sup></h3>
                <p>Taxa de Rejeição</p>
            </div>
            <div class="small-box-icon"><i class="bi bi-graph-up"></i></div>
        </div>
    </div>
    <!-- Bloco 3 -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning shadow-sm">
            <div class="inner">
                <h3>44</h3>
                <p>Registos</p>
            </div>
            <div class="small-box-icon"><i class="bi bi-person-plus"></i></div>
        </div>
    </div>
    <!-- Bloco 4 -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger shadow-sm">
            <div class="inner">
                <h3>65</h3>
                <p>Visitantes Únicos</p>
            </div>
            <div class="small-box-icon"><i class="bi bi-pie-chart"></i></div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title mb-0"><i class="bi bi-shield-check me-2"></i> Painel Administrativo</h3>
            </div>
            <div class="card-body">
                <h5 class="text-success">Autenticação concluída com sucesso!</h5>
                <p class="mb-0 text-muted">Bem-vindo ao sistema Rede Pistas. Estás num ambiente seguro e pronto para gerir a plataforma.</p>
            </div>
        </div>
    </div>
</div>
@endsection