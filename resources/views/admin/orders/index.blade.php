@extends('layouts.admin')

@section('title', 'Pedidos')
@section('pageTitle', 'Pedidos')

@section('content')
    <div class="container bg-white py-5">

        <h3 class="mb-4">Pedidos</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Recebimento</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr id="orderRow{{ $order->id }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }} - {{ $order->user->phone }}</td>
                            <td>
                                <span
                                    class="badge text-white {{ $order->delivery_type == 'retirada' ? 'bg-primary ' : 'bg-success' }}">
                                    {{ ucfirst($order->delivery_type) }}
                                </span>
                            </td>
                            <td><span id="statusBadge{{ $order->id }}"
                                    class="badge text-white bg-warning">{{ ucfirst($order->status) }}</span></td>
                            <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#orderModal{{ $order->id }}">
                                    Ver Detalhes
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Modais de Detalhes dos Pedidos -->
    @foreach ($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="modalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes do Pedido #{{ $order->id }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            <span aria-hidden="true">&times;</span>

                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Status:</strong>
                            <span id="modalStatusBadge{{ $order->id }}"
                                class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                        </p>
                        <p><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                        <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <!-- Tipo de Recebimento -->
                        <h5 class="mt-4">Forma de Recebimento:</h5>
                        <p>
                            <span
                                class="badge text-white {{ $order->delivery_type == 'retirada' ? 'bg-primary' : 'bg-success' }}">
                                {{ ucfirst($order->delivery_type) }}
                            </span>
                        </p>
                        
                        <!-- Exibir endereço se for delivery -->
                        @if ($order->delivery_type == 'delivery' && $order->address)
                            <h5 class="mt-4">Endereço de Entrega:</h5>
                            <p><strong>CEP:</strong> {{ $order->address->cep }}</p>
                            <p><strong>Rua:</strong> {{ $order->address->rua }}, {{ $order->address->numero }}</p>
                            <p><strong>Bairro:</strong> {{ $order->address->bairro }}</p>
                            <p><strong>Cidade:</strong> {{ $order->address->cidade }} - {{ $order->address->estado }}</p>
                            <p><strong>Frete:</strong> R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
                        @endif

                        <h5 class="mt-4">Produtos do Pedido:</h5>
                        <div class="table-responsive">

                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Preço Unitário</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Atualizar Status do Pedido -->
                        <h5 class="mt-4">Atualizar Status:</h5>
                        <form method="POST" id="updateStatusForm{{ $order->id }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <select name="status" class="form-control">
                                <option value="pendente" {{ $order->status == 'pendente' ? 'selected' : '' }}>Pendente
                                </option>
                                <option value="em preparo" {{ $order->status == 'em preparo' ? 'selected' : '' }}>
                                    Em Preparo</option>

                                <option value="pronto" {{ $order->status == 'pronto' ? 'selected' : '' }}>Pronto
                                </option>
                                <option value="finalizado" {{ $order->status == 'finalizado' ? 'selected' : '' }}>
                                    Finalizado
                                </option>
                            </select>
                            <button type="button" class="btn btn-success mt-2"
                                onclick="updateStatus({{ $order->id }})">
                                Atualizar Status
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function updateStatus(orderId) {
            let form = document.querySelector(`#updateStatusForm${orderId}`);
            let formData = new FormData(form);

            fetch(`/orders/${orderId}/status`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Atualiza os badges do status na tabela e no modal
                        document.querySelector(`#statusBadge${orderId}`).textContent = data.status;
                        document.querySelector(`#modalStatusBadge${orderId}`).textContent = data.status;

                        // Exibir SweetAlert de sucesso
                        Swal.fire({
                            title: "Sucesso!",
                            text: "O status do pedido foi atualizado!",
                            icon: "success",
                            confirmButtonText: "OK"
                        });
                    } else {
                        Swal.fire({
                            title: "Erro!",
                            text: "Ocorreu um erro ao atualizar o status.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                })
                .catch(error => console.log('Erro:', error));
        }
    </script>
@endsection
