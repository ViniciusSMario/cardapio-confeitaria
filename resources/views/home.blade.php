@extends('layouts.site')

@section('title', 'Bem-vindo à Confeitaria Delícia!')

@section('content')

    <div class="row">
        <!-- Seção Hero -->
        <header class="hero bg-dark text-white text-center py-5"
            style="background: url('{{ asset('images/banner.webp') }}') center/cover no-repeat;">
            <div class="container" data-aos="fade-up">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mb-3 rounded-circle" height="150px"
                    width="150px">
                <h1 class="fw-bold display-3">Aproveite <span class="text-yellow text-uppercase">descontos exclusivos</span>
                    <br> ao comprar no site!
                </h1>
                <a href="{{ route('shop') }}" class="btn btn-maroon rounded-5 btn-lg mt-3">Conferir Produtos</a>
            </div>
        </header>
    </div>

    <div class="row">
        <section class="container bg-maroon">
            <h2 class="text-center pt-5 mb-4 text-yellow text-signature-title">Nossos Doces Mais Vendidos</h2>
            <div class="row mb-5">
                @foreach ($featuredProducts as $product)
                    <div class="col-md-3 mt-2" data-aos="zoom-in">
                        <div class="card shadow-sm border-0">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                                class="card-img-top rounded-top" alt="{{ $product->name }}">

                            <div class="card-body text-center">
                                <p class="text-uppercase text-muted mb-1">{{ $product->category->name }}</p>
                                <h5 class="text-maroon">{{ $product->name }}</h5>
                                <p class="fw-bold fs-5 text-secondary">R$ {{ number_format($product->price, 2, ',', '.') }}
                                </p>
                                <button class="btn btn-maroon w-100 add-to-cart" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                    Adicionar ao carrinho
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    @foreach ($categories as $category)
        @php
            $productImages = $category->products()->whereNotNull('image')->take(3)->pluck('image')->toArray();
        @endphp
        <section class="container my-5">
            <div class="row align-items-center {{ $loop->index % 2 == 0 ? '' : 'flex-row-reverse' }}" data-aos="zoom-in">
                <div class="col-md-6">
                    @if (count($productImages) > 0)
                        @component('components.carousel', [
                            'carouselId' => 'carouselCestas' . $category->id,
                            'images' => $productImages,
                        ])
                        @endcomponent
                    @else
                        @component('components.carousel', [
                            'carouselId' => 'carouselCestas' . $category->id,
                            'images' => ['logo.jpg'],
                        ])
                        @endcomponent
                    @endif
                </div>
                <div class="col-md-6">
                    <h2 class="text-maroon text-signature-title">{{ $category->name }}</h2>
                    <p>{{ $category->description }}</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('shop', $category->id) }}" class="btn btn-maroon text-btn-comprar rounded-5">COMPRAR</a>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <section class="d-flex justify-content-center my-5">
        <a href="{{ route('categories') }}" class="btn btn-maroon rounded-5"> Ver todas as Categorias</a>
    </section>

    <div class="row">
        <!-- Sobre a Confeitaria -->
        <section class="bg-maroon text-white text-center py-5" id="sobre">
            <div class="container" data-aos="zoom-in">
                <h2 class="text-signature-title text-yellow">Sobre Nós</h2>
                <p class="lead">Nossa confeitaria nasceu da paixão por doces artesanais. <br>Utilizamos ingredientes de
                    alta
                    qualidade para trazer o melhor sabor para você!</p>
            </div>
        </section>
    </div>

        
    <!-- Botão Flutuante do WhatsApp -->
    <div class="whatsapp-container">
        <a href="https://wa.me/SEUNUMERO?text=Olá!%20Gostaria%20de%20mais%20informações." class="whatsapp-float"
            target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
        <span class="whatsapp-tooltip">Faça seu pedido no WhatsApp</span>
    </div>

    <!-- Depoimentos -->
    <section class="container my-5" id="depoimentos" data-aos="fade-up">
        <h2 class="text-center mb-4 text-signature-title text-maroon">O que nossos clientes dizem</h2>

        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner text-center p-3">
                <div class="carousel-item active">
                    <div class="card p-4 shadow-lg border-0 rounded-4">
                        <div class="d-flex justify-content-center mb-3">
                            <i class="fas fa-quote-left text-maroon fa-2x"></i>
                        </div>
                        <p class="mb-3 fs-5 fst-italic">"Os melhores doces que já comi! Recomendo demais!"</p>
                        <strong class="text-maroon">- Maria Souza</strong>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card p-4 shadow-lg border-0 rounded-4">
                        <div class="d-flex justify-content-center mb-3">
                            <i class="fas fa-quote-left text-maroon fa-2x"></i>
                        </div>
                        <p class="mb-3 fs-5 fst-italic">"A qualidade e o sabor são incríveis. Atendimento nota 10!"</p>
                        <strong class="text-maroon">- João Lima</strong>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card p-4 shadow-lg border-0 rounded-4">
                        <div class="d-flex justify-content-center mb-3">
                            <i class="fas fa-quote-left text-maroon fa-2x"></i>
                        </div>
                        <p class="mb-3 fs-5 fst-italic">"Bolos deliciosos e sempre fresquinhos. Meu lugar favorito!"</p>
                        <strong class="text-maroon">- Ana Clara</strong>
                    </div>
                </div>
            </div>

            <!-- Controles do Carrossel -->
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            let cartCount = document.getElementById("cart-count");

            function updateCartStorage() {
                localStorage.setItem("cart", JSON.stringify(cart));
                updateCartCount();
            }

            function updateCartCount() {
                let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                cartCount.textContent = totalItems;
                cartCount.style.display = totalItems > 0 ? "inline-block" : "none";
            }

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
                            price: productPrice,
                            quantity: 1
                        });
                    }

                    updateCartStorage();

                    Swal.fire({
                        position: "top",
                        icon: "success",
                        title: productName + " foi adicionado ao carrinho!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            });

            updateCartCount();
        });
    </script>

@endsection
