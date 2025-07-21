@extends('front.layout.app')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');

        body {
            font-family: 'Nunito', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-animation {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #fff;
            animation: bounce 1s infinite, colorChange 3s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes colorChange {
            0% {
                background-color: #fff;
            }

            33% {
                background-color: #aaa;
            }

            66% {
                background-color: #555;
            }

            100% {
                background-color: #000;
            }
        }

        .container {
            padding: 20px;
        }

        .product-card {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .product-card h3 {
            margin-top: 0;
        }

        .product-image {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-checkbox {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .swipper-container {
            display: none;
            width: 58%;
            padding: 20px;
            background: #ffd100;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 63px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
        }

        .swipper-slide {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .swipper-close {
            position: absolute;
            top: -3px;
            right: -8px;
            cursor: pointer;
        }

        .swipper-close i {
            font-size: 24px;
            color: #888;
        }

        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 20px;
            cursor: pointer;
        }

        .custom-checkbox input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-checkbox .checkbox-control {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .custom-checkbox .checkbox-label {
            position: absolute;
            top: 50%;
            left: 25px;
            transform: translateY(-50%);
            color: #333;
            font-size: 14px;
        }

        .custom-checkbox input:checked+.checkbox-control {
            background-color: #00ff00;
            border-color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .custom-checkbox input:checked+.checkbox-control::before {
            content: '';
        }

        .product-card {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            transition: transform 0.3s ease;
        }

        .product-card.selected {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .product-card.selected:focus {
            outline: none;
        }

        .product-price {
            position: absolute;
            bottom: 0px;
            right: 7px;
        }

        .header {
            background-color: #000;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header i {
            cursor: pointer;
            margin-right: 10px;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .footer .total-price {
            font-weight: bold;
        }

        .footer button {
            padding: 10px 20px;
            background-color: #ffd100;
            border: none;
            border-radius: 5px;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }

        .footer button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .crust-options {
            margin: 20px 0;
        }

        .crust-option {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .crust-option input {
            margin-right: 10px;
        }

        .observation {
            display: flex;
            margin-top: 20px;
            flex-direction: column;
            position: relative;
        }

        .observation i {
            margin-right: 10px;
        }

        .observation textarea {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        .char-count {
            position: absolute;
            bottom: -20px;
            right: 2px;
        }

        .observation {
            display: none;
        }

        .broto-option {
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .broto-label {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            border: 2px solid #000000;
            border-radius: 8px;
            background-color: white;
            color: #000000;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        #brotoCheckbox:checked+.broto-label {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
        }
    </style>
@endsection

@section('content')
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
    <div class="header">
        <a href="{{ route('checkout.home') }}" class="header">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar</span>
        </a>
    </div>


    <div class="container">
        <div class="broto-option">
            <input type="checkbox" id="brotoCheckbox" name="broto" hidden>
            <label for="brotoCheckbox" class="broto-label">
                🍕 Pizza Broto (Pequena)
            </label>
        </div>

        <h2>Escolha 2 Sabores</h2>
        <div class="product-list">
            @foreach ($products as $product)
                <div class="product-card" data-product-id="{{ $product->id }}">
                    <div class="product-image">
                        <img src="https://media.istockphoto.com/id/1412974054/pt/vetorial/spicy-pepperoni-pizza-icon.jpg?s=612x612&w=0&k=20&c=zpyXdIWeCzWZBvPc5hg34oo3Q5u1TNaQLS2PeM6NhWQ="
                            alt="{{ $product->name }}">
                    </div>
                    <div class="product-details">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-description">{{ $product->description }}</div>
                    </div>
                    <h3 style="display: none">{{ $product->name }}</h3>
                    <p style="display: none">{{ $product->description }}</p>
                    <div class="product-price" data-original-price="{{ $product->price }}">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </div>
                    <input type="checkbox" class="product-checkbox">
                </div>
            @endforeach
        </div>
        <h2>Bordas e Observações</h2>
        <input type="hidden" name="is_broto" id="isBrotoInput" value="0">
        @if (count($crusts) > 0)
            <div class="crust-options">
                @foreach ($crusts as $crust)
                    <div class="crust-option">
                        <input type="radio" name="crust" value="{{ $crust->id }}" data-price="{{ $crust->price }}"
                            {{ $loop->first ? 'checked' : '' }}>
                        {{ $crust->name }} <span class="crust-price">+ R$
                            {{ number_format($crust->price, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="swipper-container" id="swipper-container">
                <div class="swipper-close" id="swipper-close">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="swiper-wrapper">
                    <!-- Swiper slides will be appended here -->
                </div>
            </div>

            <div class="observation">
                <i class="fa fa-pencil"></i><small>Pizza 1</small>
                <textarea id="observation1" rows="2" maxlength="140" placeholder="Alguma Observação?"></textarea>
                <div class="char-count" id="char-count1">0/140</div>
            </div>

        
        @endif
    </div>
    <div class="sobe" style="margin-top: 116px;"></div>
    <div class="footer">
        <div class="total-price">Total: R$ <span id="totalPrice">0.00</span></div>
        <button id="addToCartButton" disabled>Adicionar ao Carrinho</button>
    </div>
@endsection

@section('scripts')
    <script>
        const brotoCheckbox = document.getElementById('brotoCheckbox');
        const isBrotoInput = document.getElementById('isBrotoInput');

        brotoCheckbox.addEventListener('change', function() {
            const isBroto = brotoCheckbox.checked;
            const priceElements = document.querySelectorAll('.product-price');

            priceElements.forEach(el => {
                const originalPrice = parseFloat(el.dataset.originalPrice);
                let newPrice = originalPrice;

                if (isBroto) {
                    newPrice = Math.max(originalPrice - 10, 0); // evita preço negativo
                }

                el.textContent = `R$ ${newPrice.toFixed(2).replace('.', ',')}`;
            });
            isBrotoInput.value = brotoCheckbox.checked ? 1 : 0;
            updateTotalPrice();
            updateAddToCartButton();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');

            function atualizarVisibilidadeProdutos() {
                const selecionados = Array.from(checkboxes).filter(cb => cb.checked);

                if (selecionados.length === 2) {
                    checkboxes.forEach(cb => {
                        const card = cb.closest('.product-card');
                        if (!cb.checked) {
                            card.style.display = 'none';
                        }
                    });
                } else {
                    // Mostra todos novamente
                    checkboxes.forEach(cb => {
                        const card = cb.closest('.product-card');
                        card.style.display = '';
                    });
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', atualizarVisibilidadeProdutos);
            });
        });
    </script>
    <script>
        $(document).ajaxStart(function() {
            $('#global-loader').css('display', 'flex'); // exibe com flex
        });

        $(document).ajaxStop(function() {
            $('#global-loader').css('display', 'none'); // esconde
        });
    </script>

    <script>
        const productCards = document.querySelectorAll('.product-card');
        const swipperContainer = document.getElementById('swipper-container');
        const swipperClose = document.getElementById('swipper-close');
        const totalPriceElement = document.getElementById('totalPrice');
        const addToCartButton = document.getElementById('addToCartButton');

        let selectedProducts = [];
        let selectedCrust = null;

        document.getElementById('observation1').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('char-count1').innerText = charCount + '/140';
            document.getElementById('observation-input1').value = this.value;
        });

     

        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const checkbox = this.querySelector('.product-checkbox');
                const productName = this.querySelector('.product-title').textContent;

                if (checkbox.checked) {
                    checkbox.checked = false;
                    const index = selectedProducts.indexOf(productId);
                    if (index > -1) {
                        selectedProducts.splice(index, 1);
                    }
                    updateObservationText(productId, '');
                } else {
                    if (selectedProducts.length >= 2) {
                        alert('Você só pode selecionar até dois sabores.');
                        return;
                    }
                    checkbox.checked = true;
                    console.log(selectedProducts);
                    selectedProducts.push(productId);
                    updateObservationText(productId, productName);
                }

                updateSwiper();

                // Adiciona/Remove a classe 'selected' para aplicar o efeito ao card selecionado
                this.classList.toggle('selected', checkbox.checked);

                updateTotalPrice();
                updateAddToCartButton();
            });
        });

        function updateObservationText() {
            const observations = document.querySelectorAll('.observation');

            // Define as observações com base nos produtos selecionados
            selectedProducts.forEach((productId, index) => {
                const productName = document.querySelector(
                    `.product-card[data-product-id="${productId}"] .product-title`).textContent;
                observations[index].querySelector('small').innerHTML = productName;
                observations[index].style.display = 'flex'; // Exibe a observação
            });

            // Oculta observações restantes
            for (let i = selectedProducts.length; i < observations.length; i++) {
                observations[i].style.display = 'none';
            }
        }


        // Adicione um evento de clique para os elementos .crust-option
        const crustOptions = document.querySelectorAll('.crust-option');
        crustOptions.forEach(option => {
            option.addEventListener('click', function() {
                const radioInput = this.querySelector('input[type="radio"]');
                if (!radioInput.checked) {
                    radioInput.checked = true;
                    selectedCrust = radioInput.value;
                    updateTotalPrice();
                }
            });
        });
        document.querySelectorAll('.crust-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('click', function() {
                selectedCrust = this.value;
                updateTotalPrice();
            });
        });

        function updateSwiper() {
            const swiperWrapper = document.querySelector('.swiper-wrapper');
            swiperWrapper.innerHTML = '';

            if (selectedProducts.length === 1) {
                selectedProducts.forEach(productId => {
                    const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`)
                        .textContent;
                    const slide = document.createElement('div');
                    slide.classList.add('swiper-slide');
                    slide.textContent = "Selecione Mais 1  Sabor";
                    swiperWrapper.appendChild(slide);
                });
            } else if (selectedProducts.length === 2) {
                selectedProducts.forEach(productId => {
                    const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`)
                        .textContent;
                    const slide = document.createElement('div');
                    slide.classList.add('swiper-slide');
                    slide.textContent = "Metade : " + productName;
                    swiperWrapper.appendChild(slide);
                });
            }

            // Oculta produtos não selecionados se dois estiverem marcados
            const allCards = document.querySelectorAll('.product-card');
            if (selectedProducts.length === 2) {
                allCards.forEach(card => {
                    const id = card.getAttribute('data-product-id');
                    if (!selectedProducts.includes(id)) {
                        card.style.display = 'none';
                    }
                });
            } else {
                allCards.forEach(card => {
                    card.style.display = ''; // volta ao normal
                });
            }

            // else if (selectedProducts.length > 2) {
            //     const remainder = selectedProducts.length - 2;
            //     selectedProducts.slice(0, 2).forEach(productId => {
            //         const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`)
            //             .textContent;
            //         const slide = document.createElement('div');
            //         slide.classList.add('swiper-slide');
            //         slide.textContent = "1/3 terço : " + productName;
            //         swiperWrapper.appendChild(slide);
            //     });
            //     selectedProducts.slice(2).forEach(productId => {
            //         const productName = document.querySelector(`.product-card[data-product-id="${productId}"] h3`)
            //             .textContent;
            //         const slide = document.createElement('div');
            //         slide.classList.add('swiper-slide');
            //         slide.textContent = "1/3 terço : " + productName;
            //         swiperWrapper.appendChild(slide);
            //     });
            // }

            if (selectedProducts.length > 0) {
                swipperContainer.style.display = 'block';
            } else {
                swipperContainer.style.display = 'none';
            }

            if (selectedProducts.length > 0) {
                swipperContainer.style.display = 'block';
            } else {
                swipperContainer.style.display = 'none';
            }
        }

        function updateTotalPrice() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const brotoAtivo = document.getElementById('brotoCheckbox').checked;
            let maiorPreco = 0;

            checkboxes.forEach(cb => {
                const card = cb.closest('.product-card');
                const priceText = card.querySelector('.product-price').textContent;
                let preco = parseFloat(priceText.replace('R$ ', '').replace(',', '.'));

                // if (brotoAtivo) {
                //     preco = Math.max(0, preco - 10); // Aplica desconto de R$10, nunca deixa negativo
                // }

                if (preco > maiorPreco) {
                    maiorPreco = preco;
                }
            });

            // Soma preço da borda, se houver
            const crustSelected = document.querySelector('input[name="crust"]:checked');
            if (crustSelected) {
                const precoBorda = parseFloat(crustSelected.dataset.price);
                maiorPreco += precoBorda;
            }

            document.getElementById('totalPrice').textContent = maiorPreco.toFixed(2).replace('.', ',');
        }

        function updateAddToCartButton() {
            if (selectedProducts.length >= 2) {
                addToCartButton.disabled = false;
            } else {
                addToCartButton.disabled = true;
            }
        }

        swipperClose.addEventListener('click', function() {
            swipperContainer.style.display = 'none';
            productCards.forEach(card => {
                card.querySelector('.product-checkbox').checked = false;
                card.classList.remove('selected');
            });
            selectedProducts = [];
            updateTotalPrice();
            updateAddToCartButton();
        });

        document.getElementById('addToCartButton').addEventListener('click', function() {
            const productIds = selectedProducts;
            let crustId = selectedCrust;
            if (crustId === null) {
                crustId = 1; // Defina 1 como o valor padrão se nenhum tipo de borda for selecionado
            }
            const observation1 = document.getElementById('observation1').value;

            const formData = new FormData();
            formData.append('product_ids', JSON.stringify(productIds));
            formData.append('crust_id', crustId);
            formData.append('observation1', observation1);
            formData.append('_token', '{{ csrf_token() }}');
            if (brotoCheckbox && brotoCheckbox.checked) {
                formData.append('is_broto', '1');
            }

            fetch('{{ route('cart.add2') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao adicionar produto ao carrinho');
                    }
                    return response.json();
                })
                .then(data => {
                    // Redirecionar para a página de checkout, se necessário
                    window.location.href = '{{ route('checkout.home') }}';
                })
                .catch(error => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: "Adicionado com Sucesso",
                    })

                    setTimeout(() => {
                        window.location.href = '{{ route('checkout.home') }}';
                    }, 1000);
                });
        });
    </script>
@endsection
