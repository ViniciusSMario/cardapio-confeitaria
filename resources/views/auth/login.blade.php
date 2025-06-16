@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="text-center">Login</h3>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="identifier" class="form-label">E-mail ou Telefone</label>
                <input type="text" name="identifier" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</div>
@endsection
