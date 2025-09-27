<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resumo do Carrinho</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
            background: white;
        }

        .item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .item img {
            max-width: 100px;
            margin-right: 20px;
            float: left;
        }

        .item-details {
            overflow: hidden;
        }

        .item-details h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .item-details p {
            margin: 0;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Estilo para o modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {

            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }

        /* Estilo para o card de observações */
        .observation-card {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .observation-card h3 {
            margin-top: 0;
        }

        .observation {
            margin-bottom: 5px;
        }

        .row {

            display: flex;
            flex-direction: row;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            text-align: center;
            color: white;
            font-size: 20px;
            font-family: Arial, sans-serif;
        }

        .loading-overlay .loading-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .icons a {
            cursor: pointer;
            text-align: center;
            font-size: 28px;
            color: var(--green);
            -webkit-text-stroke-width: 1px;
            -webkit-text-stroke-color: #5cc011;

        }
    </style>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .receipt-container {
            max-width: 500px;
            margin: auto;
            padding: 15px;
            border: 1px solid #eee;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .title {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        section {
            margin-bottom: 15px;
        }

        .item-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .item-line {
            font-size: 14px;
            margin: 2px 0;
        }

        .totals {
            font-size: 15px;
        }

        .total-final {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .observations {
            background: #fff9e6;
            padding: 8px;
            border-left: 3px solid #f0b400;
            margin-top: 5px;
            border-radius: 5px;
        }

        .observations ul {
            margin: 5px 0 0 15px;
            padding: 0;
            font-size: 13px;
        }

        hr {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .whatsapp-btn {
            display: block;
            text-align: center;
            background-color: #25d366;
            color: white;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
        }

        .whatsapp-btn i {
            margin-right: 5px;
        }

        @media (max-width: 480px) {
            .receipt-container {
                padding: 10px;
            }

            .item-line,
            .totals,
            .payment-info p {
                font-size: 13px;
            }

            .title {
                font-size: 18px;
            }
        }
    </style>

</head>

<body>
    <div class="loading-overlay">
        <div class="loading-text">Por favor, aguarde...</div>
    </div>

    <div class="receipt-container">
        <h1 class="title">Resumo do Pedido</h1>

        <section class="customer-info">
            <h2>Cliente</h2>
            <p><strong>Nome:</strong> {{ $customer->name }}</p>
            <p><strong>Telefone:</strong> {{ $customer->phone }}</p>
            <p><strong>Endereço:</strong> {{ $customer->public_place }} Nº {{ $customer->number }}</p>
            <p><strong>Bairro:</strong> {{ $customer->neighborhood }}</p>
        </section>

        <hr>

        @foreach ($cart as $item)
            @php
                $itemDetails = explode('/', $item['name']);
                $flavors = array_map('trim', $itemDetails);
                $flavorsCount = count($flavors);

                $pizzaTitle =
                    $flavorsCount > 1
                        ? implode(' / ', $flavors) // Exibe "Sabor 1 / Sabor 2"
                        : $flavors[0];

                $observations = explode(' / ', $item['observation']);
            @endphp

            <section class="item">
                <h3 class="item-title">{{ $pizzaTitle }}</h3>
               
                <p class="item-line"><strong>Borda:</strong> {{ $item['crust'] }} (R$
                    {{ number_format($item['crust_price'], 2, ',', '.') }})</p>
                <p class="item-line"><strong>Quantidade:</strong> {{ $item['quantity'] }}</p>
                <p class="item-line"><strong>Preço Unitário:</strong> R$
                    {{ number_format($item['price'], 2, ',', '.') }}</p>
                <p class="item-line"><strong>Total:</strong> R$ {{ number_format($item['total'], 2, ',', '.') }}</p>

                @if (!empty($observations))
                    <div class="observations">
                        <strong>Observações:</strong>
                        <ul>
                            @foreach ($observations as $index => $obs)
                                @if (!empty(trim($obs)))
                                    <li><em>{{ $itemDetails[$index] ?? 'Pizza' }}:</em> {{ $obs }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>
            <hr>
        @endforeach

        <section class="totals">
            <p><strong>Subtotal:</strong> R$ {{ number_format(array_sum(array_column($cart, 'total')), 2, ',', '.') }}
            </p>
            
            @php
                $promocaoAtiva = env('PROMOCAO_FRETE_GRATIS_ACIMA_2_PIZZAS', false);
                $totalPizzas = 0;
                
                // Contar pizzas no carrinho
                foreach ($cart as $item) {
                    $productIds = explode(',', $item['product_id']);
                    foreach ($productIds as $productId) {
                        $product = \App\Models\Product::with('category')->find($productId);
                        if ($product && $product->category && stripos($product->category->name, 'pizza') !== false) {
                            $totalPizzas += $item['quantity'];
                            break;
                        }
                    }
                }
            @endphp
            
            @if($promocaoAtiva && $taxaEntrega == 0 && $totalPizzas >= 2)
                <p style="color: #28a745; font-weight: bold;">
                    🎉 Promoção Ativa: Frete Grátis para 2+ Pizzas!
                </p>
            @endif
            
            <p><strong>Taxa de Entrega:</strong> R$ {{ number_format($taxaEntrega, 2, ',', '.') }}</p>
            <p class="total-final"><strong>Total:</strong> R$ {{ number_format($totalPrice, 2, ',', '.') }}</p>
        </section>

        <hr>

        <section class="payment-info">
            <h2>Pagamento</h2>
            <p><strong>Método:</strong> {{ $paymentMethod }}</p>
            @if ($paymentMethod == 'Dinheiro' && !empty($trocoAmount))
                <p><strong>Troco para:</strong> R$ {{ number_format($trocoAmount, 2, ',', '.') }}</p>
            @endif
        </section>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <h2>Obrigado pelo seu pedido!</h2>
                <p>Seu pedido foi recebido com sucesso. Retorne para o WhatsApp.</p>
                <a class="whatsapp-btn" href="https://api.whatsapp.com/send?phone=5511972920248">
                    <i class="fa-brands fa-whatsapp"></i> Clique aqui!
                </a>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<script>
    // Função para exibir o modal de agradecimento
    function showModal() {
        $('#myModal').css('display', 'block');
        $('.loading-overlay').css('display', 'none');
    }

    // Função para enviar a imagem via AJAX e exibir o modal
    function sendImageAndShowModal() {
        // Mostrar o overlay de carregamento


        // Tirar o print da página
        html2canvas(document.body).then(function(canvas) {
            // Converter o canvas em uma imagem
            var imgData = canvas.toDataURL('image/png');
            var token = $('meta[name="csrf-token"]').attr('content');
            // Enviar a imagem via AJAX
            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.enviaImagen') }}',
                data: {
                    _token: token,
                    imagem: imgData
                },
                success: function(response) {

                    showModal();
                },
                error: function(xhr, status, error) {

                    $('.loading-overlay').css('display', 'none');
                }
            });
        });

        $('.loading-overlay').css('display', 'block');
    }

    // Chamada da função ao carregar a página
    $(document).ready(function() {
        sendImageAndShowModal();
    });
</script>


</html>
