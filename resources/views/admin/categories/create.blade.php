@extends('layouts.admin')

@section('title', 'Nova Categoria')

@section('content')
<div class="container bg-white py-5">
    <h3 class="mb-3">Nova Categoria</h3>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Categoria</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
