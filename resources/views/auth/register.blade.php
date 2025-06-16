@extends('layouts.app')

@section('title', 'Registrar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="text-center">Criar Conta</h3>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telefone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrar</button>
        </form>
    </div>
</div>
@endsection
