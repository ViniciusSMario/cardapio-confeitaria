@extends('layouts.admin')

@section('title', 'Categorias')

@section('content')
    <div class="container bg-white py-5">
        <h3 class="mb-3">Categorias</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">Nova Categoria</a>

        @if(session('success'))
            <script>
                Swal.fire({
                    title: "Sucesso!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            </script>
        @endif

        <div class="table-responsive">

            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-wrap">{{ $category->description ?? 'Sem descrição' }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}"
                                    class="btn btn-primary btn-sm">Editar</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
