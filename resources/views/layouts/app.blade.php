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
</head>
<body>
    @include('components.navbar')
    <div class="container mt-4">
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#phone').mask('(99) 99999-9999');
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
