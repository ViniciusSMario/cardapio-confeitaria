@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-maroon text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle"></i> Pedido Confirmado!</h4>
                </div>
                <div class="card-body">
                    <img src="{{ asset('images/success.png') }}" alt="Pedido realizado" class="img-fluid mb-4" style="max-width: 250px;">
                    <h5 class="text-success">Seu pedido foi realizado com sucesso!</h5>
                    <p class="text-muted">Acompanhe seu pedido pelo seu e-mail ou em nosso painel de pedidos.</p>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-maroon rounded-pill">
                            <i class="fas fa-home"></i> Voltar para a Página Inicial
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
