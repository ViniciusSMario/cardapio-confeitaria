<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cardápio Confeitaria')</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- AOS Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

</head>

<body>
    @include('components.navbar')
    <div class="container-fluid">
        @yield('content')
        
        <div class="row">
            <footer class="bg-maroon text-center text-white p-5">
                <div class="row">
                    <div class="col-md-4">
                        <h2>Links Úteis</h2>
                        <ul class="list-unstyled">
                            <li class="fw-bold">Minha Conta</li>
                            <li class="fw-bold">Perguntas Frequentes</li>
                            <li class="fw-bold">Política de Privacidade</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="img-fluid rounded-circle"
                            width="100px">
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <i class="fa-brands fa-whatsapp social-icon text-whatsapp"></i>
                            </div>
                            <div class="col-md-4">
                                <i class="fa-brands fa-instagram social-icon text-instagram"></i>
                            </div>
                            <div class="col-md-4">
                                <i class="fa-brands fa-facebook social-icon text-facebook"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h2>Onde Estamos?</h2>
                        <p>Rua Tal de Souza, 782 - São José do Rio Pardo/SP</p>
                        <p><strong>Segunda à Sábado:</strong> das <strong>12h</strong> as <strong>19h</strong>.</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <div class="copyright text-center my-auto">
                            <span>&copy; Todos os direitos reservados <br> AmanDoces -
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

    <!-- AOS Animation Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#phone').mask('(99) 99999-9999');
        });
        AOS.init({
            duration: 1000, // Duração da animação (1s)
            once: true, // Animação só acontece uma vez
        });

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


</body>

</html>
