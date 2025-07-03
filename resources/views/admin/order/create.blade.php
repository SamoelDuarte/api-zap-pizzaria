@extends('admin.layout.app')

@section('content')
    <div class="page-header-content py-3">
        <h1 class="h3 mb-0 text-gray-800">Novo Pedido</h1>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <form action="" id="formPedido" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3 position-relative">
                                    <label for="telefone" class="form-label">Telefone do Cliente</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"
                                        autocomplete="off" required
                                        oninvalid="this.setCustomValidity('O campo Telefone deve ser preenchido.')"
                                        oninput="this.setCustomValidity('')">

                                    <div id="sugestoes-clientes" class="list-group position-absolute w-100 d-none"
                                        style="z-index: 1000;"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="logradouro" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro"
                                        autocomplete="off" required
                                        oninvalid="this.setCustomValidity('O campo Logradouro deve ser preenchido.')"
                                        oninput="this.setCustomValidity('')">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <!-- Número -->
                                    <input type="text" class="form-control" id="numero" name="numero" required
                                        oninvalid="this.setCustomValidity('O campo Número deve ser preenchido.')"
                                        oninput="this.setCustomValidity('')">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="referencia" class="form-label">Ponto de Referência</label>
                                    <input type="text" class="form-control" id="referencia" name="referencia">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro">
                                </div>
                            </div>

                        </div>


                        <hr class="my-4">

                        <div class="col-md-12">
                            <!-- Produto Unidade (sem código) -->
                            <div class="row align-items-end mt-3">
                                <div class="col-md-2 position-relative">
                                    <label class="form-label">Produto Unidade</label>
                                    <input type="text" class="form-control" id="produto_unidade_nome"
                                        placeholder="EX: Margarita" autocomplete="off">
                                    <div id="sugestoes-produtos-unidade" class="list-group position-absolute w-100 d-none"
                                        style="z-index: 1000;"></div>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Qtd.</label>
                                    <input type="number" class="form-control" id="produto_unidade_qtd" value="1"
                                        min="1">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Valor (R$)</label>
                                    <input type="text" class="form-control" id="produto_unidade_valor"
                                        placeholder="Ex: 49.90">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Borda</label>
                                    <select class="form-select form-control" id="produto_unidade_borda">
                                        @foreach ($crusts as $crust)
                                            <option value="{{ $crust->name }}" data-preco="{{ $crust->price }}">
                                                {{ $crust->name }} (+R$ {{ number_format($crust->price, 2, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Observação</label>
                                    <input type="text" class="form-control" id="produto_unidade_obs"
                                        placeholder="Ex: Sem cebola">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2 mt-3" id="lista_produtos"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <!-- Select Forma Pagamento -->
                                <div class="col-md-5">
                                    <h5>Forma de Pagamento</h5>
                                    <div class="crust-options">
                                        <label class="crust-option">
                                            <input type="radio" name="forma_pagamento" value="Dinheiro"
                                                class="payment-radio">
                                            <i class="fas fa-check"></i>
                                            <span class="pizza-price">Dinheiro</span>
                                        </label>
                                        <div id="dinheiro-options" style="display: none; margin-left: 20px;">
                                            <label>
                                                <input type="checkbox" id="troco-checkbox"> Precisa de troco?
                                            </label>
                                            <div id="troco-field" style="display: none;">
                                                <label for="troco-amount">Valor do troco:</label>
                                                <input type="text" id="troco-amount" class="form-control money"
                                                    name="troco_amount" placeholder="0,00">
                                            </div>
                                        </div>

                                        <label class="crust-option">
                                            <input type="radio" name="forma_pagamento" value="Cartão de Crédito"
                                                class="payment-radio">
                                            <i class="fas fa-check"></i>
                                            <span class="pizza-price">Cartão de Crédito</span>
                                        </label>

                                        <label class="crust-option">
                                            <input type="radio" name="forma_pagamento" value="Cartão de Débito"
                                                class="payment-radio">
                                            <i class="fas fa-check"></i>
                                            <span class="pizza-price">Cartão de Débito</span>
                                        </label>

                                        <label class="crust-option">
                                            <input type="radio" name="forma_pagamento" value="Pix"
                                                class="payment-radio">
                                            <i class="fas fa-check"></i>
                                            <span class="pizza-price">Pix</span>
                                        </label>

                                        <div id="pix-options" style="display: none; margin-left: 20px;">
                                            <p>Use o seguinte código PIX para pagamento:</p>
                                            <div class="d-flex align-items-center">
                                                <textarea id="pix-code" rows="2" class="form-control" readonly>0002012636pix.exemplo@forna.com5204000053039865405100.005802BR5920FORNA PIZZA6009SAO PAULO61080540900062290525chavepixexemplo6304ABCD</textarea>
                                                <button type="button" id="copy-pix-btn"
                                                    class="btn btn-sm btn-secondary ms-2">Copiar</button>
                                            </div>
                                            <small class="text-danger d-block mt-1">Após efetuar o pagamento, retorne
                                                para finalizar o pedido.</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Valor Pagamento -->
                                <div class="col-md-4">
                                    <label class="form-label">Valor (R$)</label>
                                    <input type="number" class="form-control" id="valor_pagamento" step="0.01">
                                </div>
                                <!-- Botão Adicionar Pagamento -->
                                <div class="col-md-1 align-items-center d-flex">
                                    <button type="button" class="btn btn-primary" id="btnAdicionarPagamento">+</button>
                                </div>
                                <!-- Lista de formas de pagamento -->
                                <div class="row g-2 mt-3" id="lista_pagamentos"></div>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-end">
                                <!-- Total Geral -->
                                <div class="mt-4">
                                    <h5>Total Geral: R$ <span id="total_geral">0.00</span></h5>
                                    <h6 class="text-muted">Falta pagar: R$ <span id="total_falta">0.00</span></h6>
                                    <h6>Total Pago: R$ <span id="total_pago">0.00</span></h6>
                                    <!-- Novo campo para mostrar o troco -->
                                    <h6 class="text-success"><span id="troco"></span></h6>
                                </div>

                                <div id="inputs_ocultos"></div> <!-- aqui os JS vão inserir os hidden inputs -->
                                <input type="hidden" name="total" id="total_geral_input">


                                <button type="submit" class="btn btn-primary" id="salvar-pedido">Salvar
                                    Pedido</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer-shortcut bg-light border-top py-2 px-3 fixed-bottom shadow-sm">
            <div class="d-flex justify-content-between align-items-center small text-muted">
                <div>
                    <strong>Atalhos:</strong>
                    <span class="ms-3">[ Alt + T - Telefone ] </span>
                    <span class="ms-3">[ Alt + P - Produto ] </span> <!-- "U" de "produto" (já que P tá em uso) -->
                    <span class="ms-3">[ Alt + M - Pagamento ] </span> <!-- "M" de "forma de pagamento" -->
                    <span class="ms-3">[ Alt + R - Rua ] </span> <!-- "M" de "forma de pagamento" -->
                    <span class="ms-3">[ Alt + S - Salvar Pedido ] </span> <!-- "M" de "forma de pagamento" -->

                </div>
                <div>
                    <span>Pressione os atalhos para facilitar o uso</span>
                </div>
            </div>
        </footer>
    </div>

    </div>
@endsection


@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw&libraries=places">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let buscaBloqueada = false;
            const telefoneInput = document.getElementById('telefone');
            const sugestoesBox = document.getElementById('sugestoes-clientes');
            let sugestoes = [];
            let indexSelecionado = -1;
            let ultimoTelefoneBuscado = '';

            function preencherCampos(cliente) {
                let telefoneLimpo = cliente.jid.replace(/^55/, '');
                telefoneInput.value = telefoneLimpo;
                document.getElementById('nome').value = cliente.name ?? '';
                document.getElementById('logradouro').value = cliente.public_place ?? '';
                document.getElementById('numero').value = cliente.number ?? '';
                document.getElementById('cep').value = cliente.zipcode ?? '';
                document.getElementById('bairro').value = cliente.neighborhood ?? '';
                document.getElementById('cidade').value = cliente.city ?? '';
                document.getElementById('referencia').value = cliente.reference ?? '';

                buscaBloqueada = true;
                sugestoesBox.classList.add('d-none');

                document.getElementById('nome').focus();
            }

            function buscarTelefone(valor) {
                if (valor.length < 8 || valor === ultimoTelefoneBuscado) return;

                ultimoTelefoneBuscado = valor;

                fetch(`/clientes/buscar-por-telefone?telefone=${encodeURIComponent(valor)}`)
                    .then(res => res.json())
                    .then(data => {
                        sugestoes = data;
                        sugestoesBox.innerHTML = '';
                        indexSelecionado = -1;

                        if (
                            data.length === 1 &&
                            data[0].jid.replace(/\D/g, '').endsWith(valor.replace(/\D/g, ''))
                        ) {
                            preencherCampos(data[0]);
                            sugestoesBox.classList.add('d-none');
                        } else if (data.length) {
                            data.forEach((cliente, index) => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.classList.add('list-group-item', 'list-group-item-action');

                                let telefoneExibido = cliente.jid.replace(/^55/, '');
                                item.textContent =
                                    `${telefoneExibido} - ${cliente.name} - ${cliente.public_place ?? ''}`;
                                item.dataset.index = index;

                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    preencherCampos(cliente);
                                    sugestoesBox.classList.add('d-none');
                                });

                                sugestoesBox.appendChild(item);
                            });
                            sugestoesBox.classList.remove('d-none');
                        } else {
                            sugestoesBox.classList.add('d-none');
                        }
                    });
            }

            telefoneInput.addEventListener('input', function() {
                if (buscaBloqueada) {
                    if (telefoneInput.value.trim() !== ultimoTelefoneBuscado) {
                        buscaBloqueada = false;
                    } else {
                        return;
                    }
                }
                const valor = telefoneInput.value.trim();
                buscarTelefone(valor);
            });

            telefoneInput.addEventListener('blur', function() {
                sugestoesBox.classList.add('d-none');
            });

            telefoneInput.addEventListener('keydown', function(e) {
                const itens = sugestoesBox.querySelectorAll('.list-group-item');

                if (e.key === 'ArrowDown') {
                    if (indexSelecionado < itens.length - 1) {
                        indexSelecionado++;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionado].classList.add('active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    if (indexSelecionado > 0) {
                        indexSelecionado--;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionado].classList.add('active');
                    }
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (indexSelecionado >= 0 && sugestoes[indexSelecionado]) {
                        preencherCampos(sugestoes[indexSelecionado]);
                        sugestoesBox.classList.add('d-none');
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initGoogleAutocomplete('#logradouro', {
                logradouro: '#logradouro',
                bairro: '#bairro',
                cidade: '#cidade',
                estado: '#estado',
                cep: '#cep',
                numero: '#numero'
            });
        });

        function initGoogleAutocomplete(inputSelector, campos) {
            const input = document.querySelector(inputSelector);
            if (!input) return;

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address'],
                componentRestrictions: {
                    country: 'br'
                },
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                const components = place.address_components;

                let logradouro = '';
                let bairro = '';
                let cidade = '';
                let cep = '';
                let numero = '';

                components.forEach(component => {
                    const types = component.types;

                    if (types.includes('street_number')) {
                        numero = component.long_name;
                    }

                    if (types.includes('route')) {
                        logradouro = component.long_name;
                    }

                    if (types.includes('sublocality') || types.includes('sublocality_level_1')) {
                        bairro = component.long_name;
                    }

                    if (types.includes('administrative_area_level_2')) {
                        cidade = component.long_name;
                    }

                    if (types.includes('postal_code')) {
                        cep = component.long_name.replace(/\D/g, ''); // ✅ remove hífens e letras
                    }
                });

                // Se o número não veio de street_number, tenta extrair do endereço completo
                if (!numero && place.formatted_address) {
                    const match = place.formatted_address.match(/(\d{1,5})/);
                    if (match) {
                        numero = match[1];
                    }
                }

                // Preenche os campos
                if (campos.logradouro) document.querySelector(campos.logradouro).value = logradouro;
                if (campos.bairro) document.querySelector(campos.bairro).value = bairro;
                if (campos.cidade) document.querySelector(campos.cidade).value = cidade;
                if (campos.cep) document.querySelector(campos.cep).value = cep;
                if (campos.numero) document.querySelector(campos.numero).value = numero;
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const telefoneInput = document.querySelector('#telefone');

            telefoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // Se não tem nada, não aplica máscara
                if (value.length === 0) {
                    e.target.value = '';
                    return;
                }

                if (value.length > 11) value = value.slice(0, 11);

                if (value.length > 10) {
                    // Celular com 9 dígitos: (11) 91234-5678
                    value = value.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
                } else if (value.length > 6) {
                    // Fixo com 8 dígitos: (11) 1234-5678
                    value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                } else if (value.length > 2) {
                    // Só DDD + prefixo parcial: (11) 1234
                    value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                } else {
                    // Apenas DDD parcial
                    value = value.replace(/^(\d{0,2})/, '($1');
                }

                e.target.value = value;
            });
        });
    </script>
    {{-- busca e adicao de produto --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let produtos = [];

            const inputNome = document.getElementById('produto_unidade_nome');
            const inputQtd = document.getElementById('produto_unidade_qtd');
            const inputValor = document.getElementById('produto_unidade_valor');
            const sugestoesBox = document.getElementById('sugestoes-produtos-unidade');
            const listaProdutos = document.getElementById('lista_produtos');
            const bordaSelect = document.getElementById('produto_unidade_borda');
            const inputObs = document.getElementById('produto_unidade_obs');

            let sugestoes = [];
            let indexSelecionado = -1;
            let ultimoValorBuscado = '';

            // Adiciona produto na lista
            function adicionarProdutoNaTabela(produto = null) {
                const nome = inputNome.value.trim();
                const quantidade = parseFloat(inputQtd.value) || 1;
                const valorUnitario = parseFloat(inputValor.value.replace(',', '.')) || 0;

                const bordaSelect = document.getElementById('produto_unidade_borda');
                const nomeBorda = bordaSelect.value;
                const precoBorda = parseFloat(bordaSelect.selectedOptions[0]?.dataset.preco || 0);
                const inputObs = document.getElementById('produto_unidade_obs');
                // Dentro da função adicionarProdutoNaTabela:
                console.log("preco borda" + precoBorda);

                const observacao = inputObs.value.trim();
                if (!nome || valorUnitario <= 0) {
                    alert('Preencha nome e valor corretamente.');
                    return;
                }

                const valorComBorda = valorUnitario + precoBorda;
                const total = quantidade * valorComBorda;

                produtos.push({
                    codigo: '',
                    nome: nome,
                    quantidade: quantidade,
                    valorUnitario: valorUnitario,
                    borda: nomeBorda,
                    precoBorda: precoBorda,
                    total: total,
                    observacao: observacao
                });

                if (typeof atualizarListaProdutos === 'function') {
                    atualizarListaProdutos();
                }

                // Limpa campos menos o select que volta para a primeira opção
                inputNome.value = '';
                inputQtd.value = '1';
                inputValor.value = '';
                bordaSelect.selectedIndex = 0; // volta para primeira opção
                sugestoesBox.classList.add('d-none');
                // Depois, limpa o campo de observação:
                inputObs.value = '';

                inputNome.focus();
            }

            // Preenche campos ao selecionar produto
            function preencherCampos(produto) {
                inputNome.value = produto.name;
                inputValor.value = parseFloat(produto.price).toFixed(2).replace('.', ',');
                inputQtd.value = '1';
                sugestoesBox.classList.add('d-none');

                inputQtd.focus();
                inputQtd.select();
            }

            function buscarProduto(valor) {
                if (valor.length < 2 || valor === ultimoValorBuscado) return;
                ultimoValorBuscado = valor;

                fetch(`/produtos/buscar-por-nome?nome=${encodeURIComponent(valor)}`)
                    .then(res => res.json())
                    .then(data => {
                        sugestoes = data;
                        sugestoesBox.innerHTML = '';
                        indexSelecionado = -1;

                        if (data.length) {
                            data.forEach((produto, index) => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.classList.add('list-group-item', 'list-group-item-action');
                                item.textContent =
                                    `${produto.name} - R$ ${parseFloat(produto.price).toFixed(2)}`;
                                item.dataset.index = index;

                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    preencherCampos(produto);
                                });

                                sugestoesBox.appendChild(item);
                            });
                            sugestoesBox.classList.remove('d-none');
                        } else {
                            sugestoesBox.classList.add('d-none');
                        }
                    });
            }

            inputNome.addEventListener('input', function() {
                buscarProduto(this.value.trim());
            });

            inputNome.addEventListener('keydown', function(e) {
                const itens = sugestoesBox.querySelectorAll('.list-group-item');

                if (e.key === 'ArrowDown') {
                    if (indexSelecionado < itens.length - 1) {
                        indexSelecionado++;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionado].classList.add('active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    if (indexSelecionado > 0) {
                        indexSelecionado--;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionado].classList.add('active');
                    }
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (indexSelecionado >= 0 && sugestoes[indexSelecionado]) {
                        preencherCampos(sugestoes[indexSelecionado]);
                    }
                }
            });

            inputQtd.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    inputValor.focus();
                    inputValor.select();
                }
            });
            inputValor.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    bordaSelect.focus();
                }
            });
            bordaSelect.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    inputObs.focus();
                    inputObs.select();
                }
            });

            inputObs.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    adicionarProdutoNaTabela();
                }
            });



            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key.toLowerCase() === 'a') {
                    e.preventDefault();
                    console.log('Atalho Alt+A ativado no documento');
                    adicionarProdutoNaTabela();
                }
            });


            // Atualiza a lista de produtos (unidade)
            function atualizarListaProdutos() {
                listaProdutos.innerHTML = '';

                produtos.forEach((produto, index) => {
                    const valorUnitario = produto.valorUnitario;
                    const precoBorda = produto.precoBorda || 0;
                    const valorComBorda = valorUnitario + precoBorda;
                    const valorTotal = produto.total.toFixed(2);

                    // Exibe "Tradicional" se a borda for vazia ou com valor zero
                    const nomeBordaFormatado = precoBorda > 0 ? produto.borda : 'Tradicional';
                    const observacaoFormatada = produto.observacao ? `Obs: ${produto.observacao}` : '';

                    const nomeCompleto = `${produto.nome} c/ borda ${nomeBordaFormatado}`;

                    const row = document.createElement('div');
                    row.classList.add('row', 'g-2', 'align-items-start', 'mb-2');

                    row.innerHTML = `
            <div class="col-md-10">
                <strong>${nomeCompleto}</strong> (${produto.quantidade}x) - R$ ${valorTotal}<br>
                <small class="text-muted">${observacaoFormatada}</small>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <button class="btn btn-danger btn-sm" onclick="removerProduto(${index})">Remover</button>
            </div>
        `;

                    listaProdutos.appendChild(row);
                });

                // atualizarTotais();
            }




            // Função global para remover produto da lista
            window.removerProduto = function(index) {
                produtos.splice(index, 1);
                atualizarListaProdutos();
            };
        });
    </script>
@endsection
