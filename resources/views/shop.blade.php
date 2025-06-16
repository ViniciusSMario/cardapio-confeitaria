@extends('layouts.app')

@section('title', 'Cestinha')

@section('content')
    <div class="container">
        <h3 class="text-center text-signature-title text-maroon mb-4">Nossos Produtos</h3>
        <!-- Filtros -->
        <div class="row mb-4">
            <!-- Filtros -->
            <form method="GET" action="{{ route('shop') }}">
                <div class="row mb-4">
                    <!-- Filtro por nome -->
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-maroon text-white"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search-input" class="form-control"
                                placeholder="Digite o nome do produto..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filtro por categoria -->
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-maroon text-white"><i class="fas fa-list"></i></span>
                            <select name="category_id" id="category-filter" class="form-control">
                                <option value="">Todas as Categorias</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botão de busca -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-maroon w-100">
                            <i class="fas fa-search me-2"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

        </div>

        <div class="row">
            <!-- Listagem de Produtos -->
            <div class="col-md-8">
                <div class="row" id="product-list">
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-4 product-card" data-category="{{ $product->category->id }}"
                            data-name="{{ strtolower($product->name) }}">
                            <div class="card shadow-lg border-0">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}"
                                    class="card-img-top rounded-top" alt="{{ $product->name }}">
                                <div class="card-body text-center">
                                    <p class="text-uppercase text-muted mb-1">{{ $product->category->name }}</p>
                                    <h5 class="text-maroon">{{ $product->name }}</h5>
                                    <p class="fw-bold fs-5 text-secondary">R$
                                        {{ number_format($product->price, 2, ',', '.') }}</p>

                                    <button class="btn btn-maroon w-100 add-to-cart rounded-pill"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}">
                                        <i class="fas fa-shopping-cart me-2"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>

            <!-- Carrinho de Compras (Escondido no Mobile) -->
            <div class="col-md-4 cart-container">
                <div class="card shadow">
                    <div class="card-header bg-maroon text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-basket"></i> Minha Cestinha</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="cart-items"></ul>
                        <p class="mt-3 text-center fs-5"><strong>Total: R$ <span id="cart-total">0,00</span></strong></p>
                        <button class="btn btn-danger w-100 mt-2 rounded-pill" id="clear-cart">
                            <i class="fas fa-trash-alt"></i> Limpar Carrinho
                        </button>
                        <button class="btn btn-maroon w-100 mt-2 rounded-pill" id="checkout">
                            <i class="fas fa-credit-card"></i> Finalizar Compra
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <!-- Botão Flutuante do Carrinho -->
        <button class="btn btn-maroon btn-floating" id="open-cart-modal">
            <i class="fas fa-shopping-basket"></i>
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-badge">0</span>
        </button>

        <!-- Modal do Carrinho -->
        <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-maroon text-white">
                        <h5 class="modal-title"><i class="fas fa-shopping-basket"></i> Minha Cestinha</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group" id="cart-modal-items"></ul>
                        <p class="mt-3 text-center fs-5"><strong>Total: R$ <span id="cart-modal-total">0,00</span></strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger w-100" id="clear-cart-modal"><i class="fas fa-trash-alt"></i> Limpar
                            Cestinha</button>
                        <button class="btn btn-success w-100" id="checkout-modal"><i class="fas fa-credit-card"></i>
                            Finalizar Compra</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .btn-floating {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            display: none;
            /* Somente para mobile */
        }

        .btn-floating .badge {
            font-size: 14px;
            width: 22px;
            height: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 768px) {
            .btn-floating {
                display: block;
            }

            .cart-container {
                display: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            let cartBadge = document.getElementById("cart-badge");
            let cartModalItems = document.getElementById("cart-modal-items");
            let cartModalTotal = document.getElementById("cart-modal-total");

            function updateCartModalUI() {
                cartModalItems.innerHTML = "";
                let total = 0;
                let totalItems = 0;

                cart.forEach((item, index) => {
                    total += item.price * item.quantity;
                    totalItems += item.quantity;
                    cartModalItems.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${item.name}</span>
                        <div class="d-flex align-items-center">
                            <span class="text-danger fw-bold decrement-qty-modal me-2" data-index="${index}" style="cursor: pointer;">−</span>
                            <span class="mx-2 fw-bold">${item.quantity}</span>
                            <span class="text-success fw-bold increment-qty-modal ms-2" data-index="${index}" style="cursor: pointer;">+</span>
                        </div>
                        <span class="fw-bold text-success">R$ ${(item.price * item.quantity).toFixed(2).replace(".", ",")}</span>
                        <span class="text-danger remove-from-cart-modal ms-3" data-index="${index}" style="cursor: pointer;"><i class="fas fa-trash"></i></span>
                    </li>
                `;
                });

                cartModalTotal.textContent = total.toFixed(2).replace(".", ",");
                cartBadge.textContent = totalItems;
                cartBadge.style.display = totalItems > 0 ? "inline-block" : "none";
                localStorage.setItem("cart", JSON.stringify(cart));
            }

            function updateCartUI() {
                let cartItems = document.getElementById("cart-items");
                let cartTotal = document.getElementById("cart-total");
                let cartCount = document.getElementById("cart-count"); // Atualiza na navbar
                cartItems.innerHTML = "";
                let total = 0;
                let totalItems = 0;

                cart.forEach((item, index) => {
                    total += item.price * item.quantity;
                    totalItems += item.quantity;
                    cartItems.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-bold">${item.name}</span>
                    <div class="d-flex align-items-center">
                        <span class="text-danger fw-bold decrement-qty me-2" data-index="${index}" style="cursor: pointer;">−</span>
                        <span class="mx-2 fw-bold">${item.quantity}</span>
                        <span class="text-success fw-bold increment-qty ms-2" data-index="${index}" style="cursor: pointer;">+</span>
                    </div>
                    <span class="fw-bold text-success">R$ ${(item.price * item.quantity).toFixed(2).replace(".", ",")}</span>
                    <span class="text-danger remove-from-cart ms-3" data-index="${index}" style="cursor: pointer;"><i class="fas fa-trash"></i></span>
                </li>
            `;
                });

                cartTotal.textContent = total.toFixed(2).replace(".", ",");
                cartCount.textContent = totalItems; // Atualiza o número na navbar

                if (totalItems === 0) {
                    cartCount.style.display = "none"; // Esconder badge se não houver produtos
                } else {
                    cartCount.style.display = "inline-block";
                }

                localStorage.setItem("cart", JSON.stringify(cart));
            }


            // document.getElementById("search-input").addEventListener("keyup", function() {
            //     let searchTerm = this.value.toLowerCase();
            //     document.querySelectorAll(".product-card").forEach(card => {
            //         let productName = card.getAttribute("data-name");
            //         if (productName.includes(searchTerm)) {
            //             card.style.display = "block";
            //         } else {
            //             card.style.display = "none";
            //         }
            //     });
            // });

            // document.getElementById("category-filter").addEventListener("change", function() {
            //     let selectedCategory = this.value;
            //     document.querySelectorAll(".product-card").forEach(card => {
            //         let categoryId = card.getAttribute("data-category");
            //         if (selectedCategory === "" || categoryId === selectedCategory) {
            //             card.style.display = "block";
            //         } else {
            //             card.style.display = "none";
            //         }
            //     });
            // });

            document.querySelectorAll(".add-to-cart").forEach(button => {
                button.addEventListener("click", function() {
                    let productId = this.getAttribute("data-id");
                    let productName = this.getAttribute("data-name");
                    let productPrice = parseFloat(this.getAttribute("data-price"));

                    let existingItem = cart.find(item => item.id === productId);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        cart.push({
                            id: productId,
                            name: productName,
                            price: productPrice.toFixed(2),
                            quantity: 1
                        });
                    }

                    updateCartUI();
                    updateCartModalUI();

                    // SweetAlert de sucesso
                    Swal.fire({
                        position: "top",
                        icon: "success",
                        title: "Produto adicionado à cestinha!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            });

            document.getElementById("cart-items").addEventListener("click", function(event) {
                let index = event.target.closest("[data-index]")?.getAttribute("data-index");

                if (event.target.closest(".increment-qty")) {
                    cart[index].quantity++;
                    updateCartUI();
                    updateCartModalUI();

                }

                if (event.target.closest(".decrement-qty")) {
                    if (cart[index].quantity > 1) {
                        cart[index].quantity--;
                    } else {
                        cart.splice(index, 1);
                    }
                    updateCartUI();
                    updateCartModalUI();

                }

                if (event.target.closest(".remove-from-cart")) {
                    Swal.fire({
                        title: "Tem certeza?",
                        text: "Deseja remover este item da cestinha?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Remover",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cart.splice(index, 1);
                            updateCartUI();
                            updateCartModalUI();

                            Swal.fire("Removido!", "O item foi removido.", "success");
                        }
                    });
                }
            });

            document.getElementById("clear-cart").addEventListener("click", function() {
                if (cart.length === 0) {
                    Swal.fire("A cestinha já está vazia!", "", "info");
                    return;
                }

                Swal.fire({
                    title: "Tem certeza?",
                    text: "Deseja limpar toda a cestinha?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Limpar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        updateCartUI();
                        updateCartModalUI();

                        Swal.fire("Cestinha esvaziada!", "", "success");
                    }
                });
            });

            document.getElementById("checkout").addEventListener("click", function() {
                if (cart.length === 0) {
                    Swal.fire("Sua cestinha está vazia!", "Adicione produtos antes de finalizar a compra.",
                        "warning");
                    return;
                }

                localStorage.setItem("cart", JSON.stringify(cart));
                window.location.href = "{{ route('checkout') }}";
            });

            document.getElementById("open-cart-modal").addEventListener("click", function() {
                updateCartModalUI();
                new bootstrap.Modal(document.getElementById("cartModal")).show();
            });

            cartModalItems.addEventListener("click", function(event) {
                let index = event.target.closest("[data-index]")?.getAttribute("data-index");

                if (event.target.closest(".increment-qty-modal")) {
                    cart[index].quantity++;
                    updateCartModalUI();
                }

                if (event.target.closest(".decrement-qty-modal")) {
                    if (cart[index].quantity > 1) {
                        cart[index].quantity--;
                    } else {
                        cart.splice(index, 1);
                    }
                    updateCartModalUI();
                }

                if (event.target.closest(".remove-from-cart-modal")) {
                    Swal.fire({
                        title: "Tem certeza?",
                        text: "Deseja remover este item da cestinha?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Remover",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cart.splice(index, 1);
                            updateCartModalUI();
                            Swal.fire("Removido!", "O item foi removido.", "success");
                        }
                    });
                }
            });

            document.getElementById("clear-cart-modal").addEventListener("click", function() {
                if (cart.length === 0) {
                    Swal.fire("A cestinha já está vazia!", "", "info");
                    return;
                }

                Swal.fire({
                    title: "Tem certeza?",
                    text: "Deseja limpar toda a cestinha?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Limpar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        updateCartModalUI();
                        Swal.fire("Cestinha esvaziada!", "", "success");
                    }
                });
            });

            document.getElementById("checkout-modal").addEventListener("click", function() {
                if (cart.length === 0) {
                    Swal.fire("Sua cestinha está vazia!", "Adicione produtos antes de finalizar a compra.",
                        "warning");
                    return;
                }

                localStorage.setItem("cart", JSON.stringify(cart));
                window.location.href = "{{ route('checkout') }}";
            });
            updateCartModalUI();
            updateCartUI();
        });
    </script>

@endsection
