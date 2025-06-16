@extends('layouts.admin')

@section('title', 'Lista de Produtos')

@section('content')
    <div class="container bg-white py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Lista de Produtos</h3>
            <a href="{{ route('products.create') }}" class="btn btn-success">Adicionar Produto</a>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    title: "Sucesso!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            </script>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/100' }}"
                                width="50">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td>{{ $product->category->name ?? '--Não informado--' }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Editar</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
