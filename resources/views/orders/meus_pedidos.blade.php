@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-maroon text-white text-center">
                        <h4 class="mb-0">Meus Pedidos</h4>
                    </div>
                    <div class="card-body">
                        @if (!Auth::user())
                            <form action="{{ route('meus_pedidos') }}" method="GET" class="mb-4">
                                <label for="phone" class="form-label fw-semibold">Digite seu telefone para visualizar os
                                    pedidos:</label>
                                <div class="input-group">
                                    <input type="phone" name="phone" id="phone"
                                        class="form-control rounded-start-pill"
                                        value="{{ old('phone', $phone) }}" required>
                                    <button type="submit" class="btn btn-maroon rounded-end-pill px-4">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </form>
                        @endif

                        @if (isset($userNotFound) && $userNotFound)
                            <div class="alert alert-danger text-center">
                                Nenhum usuário encontrado com este e-mail.
                            </div>
                        @elseif($orders !== null)
                            @if (count($orders) <= 0)
                                <div class="alert alert-warning text-center">Nenhum pedido encontrado para este e-mail.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="bg-maroon text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Data</th>
                                                <th>Itens</th>
                                                <th>Valor Total/Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <ul class="list-unstyled">
                                                            @foreach (json_decode($order->items) as $item)
                                                                <li>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong>
                                                        <br>
                                                        <span
                                                            class="badge 
                                                            {{ $order->status == 'pendente'
                                                            ? 'bg-warning'
                                                            : ($order->status == 'pronto' || $order->status == 'finalizado'
                                                            ? 'bg-success'
                                                            : 'bg-danger') }}"
                                                        >
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
