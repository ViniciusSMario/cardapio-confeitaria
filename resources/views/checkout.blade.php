@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div class="card-header text-white text-center bg-maroon">
                        <h4 class="mb-0">Finalizar Compra</h4>
                    </div>
                    <div class="card-body">
                        <form id="checkout-form" action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart" id="cart-data">
                            <input type="hidden" name="shipping_cost" id="shipping-cost" value="0">

                            <!-- Dados Pessoais -->
                            <h5 class="fw-bold text-secondary">Seus Dados</h5>
                            @guest
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" required name="name" id="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" required name="email" id="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telefone</label>
                                    <input type="tel" required name="phone" id="phone" class="form-control">
                                </div>
                            @else
                                <p><strong>Nome:</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                <p><strong>Telefone:</strong> {{ Auth::user()->phone ?? 'Não informado' }}</p>
                            @endguest

                            <hr>

                            <!-- Entrega -->
                            <h5 class="fw-bold text-secondary">Entrega</h5>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="radio" name="delivery_type" id="retirada"
                                    value="retirada" checked>
                                <label class="form-check-label fw-semibold" for="retirada">Retirada no Local
                                    (Grátis)</label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="delivery_type" id="delivery"
                                    value="delivery">
                                <label class="form-check-label fw-semibold" for="delivery">Delivery (Cálculo de
                                    Frete)</label>
                            </div>

                            <!-- Se o usuário estiver autenticado, mostrar opção de endereço salvo -->
                            <div id="address-selection" class="mt-3 d-none">
                                @auth
                                    <div id="savedAddress">
                                        <label for="saved-address" class="form-label fw-semibold">Usar endereço
                                            salvo:</label>
                                        <select id="saved-address" class="form-control rounded-pill">
                                            <option value="">Selecionar um endereço</option>
                                            @foreach ($addresses as $address)
                                                <option value="{{ $address->id }}" data-cep="{{ $address->cep }}"
                                                    data-rua="{{ $address->rua }}" data-numero="{{ $address->numero }}"
                                                    data-bairro="{{ $address->bairro }}" data-cidade="{{ $address->cidade }}"
                                                    data-estado="{{ $address->estado }}">
                                                    {{ $address->rua }}, {{ $address->numero }} - {{ $address->bairro }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="new-address-checkbox">
                                            <label class="form-check-label" for="new-address-checkbox">Usar um novo
                                                endereço</label>
                                        </div>
                                    </div>
                                @endauth
                            </div>

                            <!-- Campos para novo endereço -->
                            <div id="delivery-info" class="mt-3 d-none">
                                <label for="cep" class="form-label fw-semibold">CEP:</label>
                                <input type="text" id="cep" name="cep" class="form-control rounded-pill">

                                <label for="rua" class="form-label fw-semibold">Rua:</label>
                                <input type="text" id="rua" name="rua" class="form-control rounded-pill">

                                <label for="numero" class="form-label fw-semibold">Número:</label>
                                <input type="text" id="numero" name="numero" class="form-control rounded-pill">

                                <label for="bairro" class="form-label fw-semibold">Bairro:</label>
                                <input type="text" id="bairro" name="bairro" class="form-control rounded-pill">

                                <label for="cidade" class="form-label fw-semibold">Cidade:</label>
                                <select name="cidade" id="cidade" class="form-control rounded-pill">
                                    <option value="São José do Rio Pardo">São José do Rio Pardo</option>
                                </select>

                                <label for="estado" class="form-label fw-semibold">Estado:</label>
                                <select name="estado" id="estado" class="form-control rounded-pill">
                                    <option value="SP">São Paulo</option>
                                </select>

                                {{-- <button type="button" class="btn btn-info mt-2" id="calculate-shipping">Calcular
                                    Frete</button> --}}
                                <p class="text-muted mt-2" id="shipping-message"></p>
                            </div>

                            <hr>

                            <div></div>

                            <!-- Botão de finalização -->
                            <button type="submit" class="btn btn-maroon w-100 py-3">
                                <i class="fas fa-check-circle"></i> Confirmar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resumo do pedido (Fixo no lado direito em telas maiores) -->
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-white text-center bg-maroon">
                        <h5 class="mb-0">Resumo do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group mb-3" id="checkout-items"></ul>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <h4 class="fw-bold">Total:</h4>
                            <h4 class="fw-bold text-dark">R$ <span id="checkout-total">0,00</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease-in-out;
        }

        .btn {
            border-radius: 8px;
            font-weight: bold;
        }

        /* #delivery-info {
            display: none;
        } */

        @media (max-width: 768px) {
            .col-lg-5 {
                margin-top: 20px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let deliveryRadio = document.querySelector("#delivery");
            let retiradaRadio = document.querySelector("#retirada");
            let addressSelection = document.querySelector("#address-selection");
            let deliveryInfo = document.querySelector("#delivery-info");
            let newAddressCheckbox = document.querySelector("#new-address-checkbox");
            let savedAddressSelect = document.querySelector("#saved-address");
            // Atualizar itens do resumo do pedido
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            let checkoutItems = document.querySelector("#checkout-items");
            let checkoutTotal = document.querySelector("#checkout-total");
            let cartDataInput = document.querySelector("#cart-data");
            let shippingValue = document.querySelector("#shipping-cost").value;
            let total = 0;
            checkoutItems.innerHTML = "";

            if (cart.length === 0) {
                checkoutItems.innerHTML =
                    `<li class="list-group-item text-center text-muted">Seu carrinho está vazio.</li>`;
            } else {
                cart.forEach(item => {
                    total += item.price * item.quantity;
                    checkoutItems.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 shadow-sm p-3">
                            <span class="fw-bold">${item.name} <small class="text-muted">(x${item.quantity})</small></span>
                            <span class="fw-bold text-success">R$ ${(item.price * item.quantity).toFixed(2).replace(".", ",")}</span>
                        </li>
                    `;
                });
            }

            // calculateShipping()
            cartDataInput.value = JSON.stringify(cart);

            let checkoutForm = document.getElementById("checkout-form");

            checkoutForm.addEventListener("submit", function(event) {
                event.preventDefault();
                let form = this;

                Swal.fire({
                    title: "Pedido Confirmado!",
                    text: "Seu pedido foi realizado com sucesso.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        localStorage.removeItem("cart");
                        cartDataInput.value = "[]";
                    }
                });
            });

            function toggleDeliveryFields() {
                if (deliveryRadio.checked) {
                    addressSelection.classList.remove("d-none");
                    deliveryInfo.classList.remove("d-none");
                    shippingValue = 10;
                    calculateShipping(shippingValue)

                } else {
                    addressSelection.classList.add("d-none");
                    deliveryInfo.classList.add("d-none");
                    shippingValue = 0;
                    calculateShipping(shippingValue)

                }
            }

            function calculateShipping(val){
                checkoutTotal.textContent = (total + parseFloat(val)).toFixed(2).replace(".", ",");
            }

            if (deliveryRadio && retiradaRadio) {
                deliveryRadio.addEventListener("change", toggleDeliveryFields);
                retiradaRadio.addEventListener("change", toggleDeliveryFields);
            }

            if (newAddressCheckbox && savedAddressSelect) {
                newAddressCheckbox.addEventListener("change", function() {
                    if (this.checked) {
                        savedAddressSelect.value = "";
                        deliveryInfo.classList.remove("d-none");
                    } else {
                        deliveryInfo.classList.add("d-none");
                    }
                });

                savedAddressSelect.addEventListener("change", function() {
                    if (this.value) {
                        newAddressCheckbox.checked = false;
                        deliveryInfo.classList.add("d-none");
                    }
                });
            }

            toggleDeliveryFields();
        });
    </script>

@endsection
