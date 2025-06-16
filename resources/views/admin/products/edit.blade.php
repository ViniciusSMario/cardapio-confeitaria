@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('content')
<div class="container bg-white py-5">
    <h3 class="mb-3">Editar Produto</h3>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Categoria</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="" disabled>Selecione uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Imagem</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
