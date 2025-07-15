<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="samoel duarte">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>@yield('title')</title>
    @yield('css')
</head>

<body>
    <div id="global-loader"
        style="
        display: none;
        position: fixed;
        z-index: 9999;
        background-color: rgba(255,255,255,0.8);
        top: 0; left: 0; right: 0; bottom: 0;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    ">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <div style="margin-top: 10px;">Carregando...</div>
    </div>
    @yield('content')
    @yield('scripts')

    <script>
        // Mostrar loader no início do carregamento da página
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('global-loader').style.display = 'flex';
        });

        // Esconder loader após tudo (imagens, CSS, etc.) carregar
        window.addEventListener("load", function() {
            document.getElementById('global-loader').style.display = 'none';
        });

        // Mostrar loader durante requisições AJAX
        $(document).ajaxStart(function() {
            $('#global-loader').css('display', 'flex');
        });

        $(document).ajaxStop(function() {
            $('#global-loader').css('display', 'none');
        });

        // Mostrar loader ao clicar no botão "Adicionar ao Carrinho"
        document.addEventListener("DOMContentLoaded", function() {
            const addToCartBtn = document.getElementById('addToCartButton');

            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    document.getElementById('global-loader').style.display = 'flex';
                });
            }
        });
    </script>


    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: "{!! session('success') !!}",
            })
        </script>
    @endif


    @if (session('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: "{!! session('error') !!}",
            })
        </script>
    @endif
</body>

</html>
