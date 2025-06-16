@extends('layouts.admin')

@section('title', 'Editar Categoria')

@section('content')
<div class="container bg-white py-5">
    <h3 class="mb-3">Editar Categoria</h3>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Categoria</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
