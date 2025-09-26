@extends('admin.layout.app')

@section('content')
    <div class="page-header-content py-3">
        <h1 class="h3 mb-0 text-gray-800">Novo Pedido</h1>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.pedidos.finalizar') }}" id="formPedido" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3 position-relative">
                                    <label for="telefone" class="form-label">Telefone do Cliente</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"
                                        autocomplete="off"
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
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="logradouro" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro"
                                        autocomplete="off"
                                        oninvalid="this.setCustomValidity('O campo Logradouro deve ser preenchido.')"
                                        oninput="this.setCustomValidity('')">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <!-- Número -->
                                    <input type="text" class="form-control" id="numero" name="numero"
                                        oninvalid="this.setCustomValidity('O campo Número deve ser preenchido.')"
                                        oninput="this.setCustomValidity('')">
                                </div>
                            </div>
                            <div id="inputs_ocultos"></div>

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
                           <div class="col-md-1 d-flex align-items-center">
                                            <div class="form-check mt-4">
                                    <label class="form-label" for="retirada">
                                        Para retirada
                                    </label>
                                    <input class="form-input form-control" type="checkbox" id="retirada" name="retirada"
                                        value="1">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <hr class="my-4">
                                <h5 class="mt-4">🍕 Pizza 1 Sabor</h5>
                                <div class="col-md-12">
                                    <!-- Produto Unidade (sem código) -->
                                    <div class="row align-items-end mt-3">
                                        <div class="col-md-1 d-flex align-items-center">
                                            <div class="form-check mt-4">
                                                <label class="form-label" for="produto_unidade_broto">Broto</label>
                                                <input class="form-input form-control" type="checkbox"
                                                    id="produto_unidade_broto">
                                            </div>
                                        </div>
                                        <div class="col-md-2 position-relative">
                                            <label class="form-label">Produto Unidade</label>
                                            <input type="text" class="form-control" id="produto_unidade_nome"
                                                placeholder="EX: Margarita" autocomplete="off">
                                            <div id="sugestoes-produtos-unidade"
                                                class="list-group position-absolute w-100 d-none" style="z-index: 1000;">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label">Qtd.</label>
                                            <input type="number" class="form-control" id="produto_unidade_qtd"
                                                value="1" min="1">
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
                                                        {{ $crust->name }} (+R$
                                                        {{ number_format($crust->price, 2, ',', '.') }})
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

                                <h5 class="mt-4">🍕 Pizza Meia a Meia</h5>
                                <div class="col-md-12">
                                    <div class="row align-items-end mt-3">
                                        <div class="col-md-1 d-flex align-items-center">
                                            <div class="form-check mt-4">
                                                <label class="form-label" for="meia_broto">Broto</label>
                                                <input class="form-input form-control" type="checkbox" id="meia_broto">
                                            </div>
                                        </div>
                                        <div class="col-md-2 position-relative">
                                            <label class="form-label">Sabor 1</label>
                                            <input type="text" class="form-control" id="meia1_nome"
                                                autocomplete="off">
                                            <div id="sugestoes-meia1" class="list-group position-absolute w-100 d-none"
                                                style="z-index: 1000;"></div>
                                        </div>
                                        <div class="col-md-2 position-relative">
                                            <label class="form-label">Sabor 2</label>
                                            <input type="text" class="form-control" id="meia2_nome"
                                                autocomplete="off">
                                            <div id="sugestoes-meia2" class="list-group position-absolute w-100 d-none"
                                                style="z-index: 1000;"></div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">Qtd.</label>
                                            <input type="number" class="form-control" id="meia_qtd" value="1"
                                                min="1">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Valor (R$)</label>
                                            <input type="text" class="form-control" id="meia_valor"
                                                placeholder="Ex: 49.90">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Borda</label>
                                            <select class="form-select form-control" id="meia_borda">
                                                @foreach ($crusts as $crust)
                                                    <option value="{{ $crust->name }}" data-preco="{{ $crust->price }}">
                                                        {{ $crust->name }} (+R$
                                                        {{ number_format($crust->price, 2, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Observação</label>
                                            <input type="text" class="form-control" id="meia_obs"
                                                placeholder="Ex: metade sem cebola">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4">📦 Produto Comum</h5>
                                <div class="col-md-12">
                                    <div class="row align-items-end mt-3">
                                        <div class="col-md-4 position-relative">
                                            <label class="form-label">Produto</label>
                                            <input type="text" class="form-control" id="produto_simples_nome"
                                                placeholder="Ex: Coca-Cola" autocomplete="off">
                                            <div id="sugestoes-produtos-simples"
                                                class="list-group position-absolute w-100 d-none" style="z-index: 1000;">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Qtd.</label>
                                            <input type="number" class="form-control" id="produto_simples_qtd"
                                                value="1" min="1">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Valor (R$)</label>
                                            <input type="text" class="form-control" id="produto_simples_valor"
                                                placeholder="Ex: 6,00">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Observação</label>
                                            <input type="text" class="form-control" id="produto_simples_obs"
                                                placeholder="Ex: Gelada">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 ">
                                <div class="col-md-12">
                                    <div class="row g-2 mt-3" id="lista_produtos"></div>
                                    <div class="row g-2 mt-3" id="lista_pizzas_meia"></div>
                                    <div class="row g-2 mt-3" id="lista_produtos_simples"></div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column justify-content-end align-items-end">
                                            <!-- Total Geral -->
                                            <div class="mt-4">
                                                <h5>Total Geral: R$ <span id="total_geral">0.00</span></h5>
                                                <h6 class="text-muted">Falta pagar: R$ <span id="total_falta">0.00</span>
                                                </h6>
                                                <h6>Total Pago: R$ <span id="total_pago">0.00</span></h6>
                                                <!-- Novo campo para mostrar o troco -->
                                                <h6 class="text-success"><span id="troco"></span></h6>
                                            </div>

                                            <div id="inputs_ocultos"></div>
                                            <!-- aqui os JS vão inserir os hidden inputs -->
                                            <input type="hidden" name="total" id="total_geral_input">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-8">
                            <h5 class="mb-3">Forma de Pagamento</h5>

                            <div class="card p-3 mb-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-12">
                                        <!-- Opções de pagamento -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input payment-radio" type="radio"
                                                name="forma_pagamento" id="pagamento_dinheiro" value="Dinheiro" checked>
                                            <label class="form-check-label" for="pagamento_dinheiro">Dinheiro</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input payment-radio" type="radio"
                                                name="forma_pagamento" id="pagamento_credito" value="Cartão de Crédito">
                                            <label class="form-check-label" for="pagamento_credito">Cartão de
                                                Crédito</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input payment-radio" type="radio"
                                                name="forma_pagamento" id="pagamento_debito" value="Cartão de Débito">
                                            <label class="form-check-label" for="pagamento_debito">Cartão de
                                                Débito</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input payment-radio" type="radio"
                                                name="forma_pagamento" id="pagamento_pix" value="Pix">
                                            <label class="form-check-label" for="pagamento_pix">Pix</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor + Botão -->
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-4">
                                    <label for="valor_pagamento" class="form-label">Valor (R$)</label>
                                    <input type="number" class="form-control" id="valor_pagamento" step="0.01">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success mt-2"
                                        id="btnAdicionarPagamento">Adicionar</button>
                                </div>
                            </div>

                            <!-- Lista Pagamentos -->
                            <div id="lista_pagamentos" class="row g-2"></div>
                        </div>
                        <div id="inputs_ocultos_meia"></div>
                        <div id="inputs_ocultos_simples"></div>
                        <div id="inputs_pagamentos"></div>
                        <input type="hidden" name="is_broto" id="is_broto_input" value="0">
                        <button type="submit">Salvar</button>
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
@endsection


@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw&libraries=places">
    </script>
    <script>
        const inputBroto = document.getElementById('produto_unidade_broto');
        const checkboxBroto = document.getElementById('meia_broto');
    </script>

    <script>
        document.getElementById('formPedido').addEventListener('submit', function(e) {
            const pagamentosContainer = document.getElementById('inputs_pagamentos');
            const produtosContainer = document.getElementById(
                'inputs_ocultos'); // ou o container onde você insere os inputs hidden dos produtos
            const pizzasMeiaContainer = document.getElementById('inputs_ocultos_meia');
            const produtosSimplesContainer = document.getElementById('inputs_ocultos_simples');

            const temPagamento = pagamentosContainer.children.length > 0;
            // Considerando produtos: pode ter pizzas inteira, meia a meia e produto simples
            const temProduto = produtosContainer.children.length > 0 ||
                pizzasMeiaContainer.children.length > 0 ||
                produtosSimplesContainer.children.length > 0;

            if (!temProduto) {
                e.preventDefault();
                alert('Por favor, adicione pelo menos um produto ao pedido.');
                return false;
            }

            if (!temPagamento) {
                e.preventDefault();
                alert('Por favor, adicione pelo menos uma forma de pagamento.');
                return false;
            }
        });
    </script>

    {{-- susgestaode cliente --}}
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

                fetch(`/clientes/buscar-por-telefone?telefone=55${encodeURIComponent(valor)}`)
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
    {{-- retirada no local  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const retiradaCheckbox = document.getElementById('retirada');
            const numeroInput = document.getElementById('numero');
            const cepInput = document.getElementById('cep');

            retiradaCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Para retirada → zera a taxa e recalcula o total
                    criarOuAtualizarInput('delivery_fee', 0);
                    recalcularTotal();
                } else {
                    // Se desmarcar e tiver CEP + número, tenta recalcular a taxa
                    const cep = cepInput.value.replace(/\D/g, '');
                    const numero = numeroInput.value.trim();

                    if (cep.length === 8 && numero) {
                        const enderecoCompleto =
                            `${document.getElementById('logradouro').value}, ${numero}, ${document.getElementById('cidade').value}, ${document.getElementById('uf')?.value || ''}`;

                        fetch('/pedidos/calcular-taxa-entrega', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: JSON.stringify({
                                    destino: enderecoCompleto
                                })
                            })
                            .then(async response => {
                                const result = await response.json();
                                if (!response.ok) {
                                    alert(result.erro || 'Erro ao calcular a taxa de entrega.');
                                    return;
                                }
                                criarOuAtualizarInput('delivery_fee', result.taxa);
                                recalcularTotal();
                            })
                            .catch(() => {
                                alert('Erro ao se comunicar com o servidor.');
                            });
                    }
                }
            });
        });
    </script>

    {{-- busca cep --}}
    <script>
        document.getElementById('cep').addEventListener('input', function(e) {
            let cep = e.target.value.replace(/\D/g, '').substring(0, 8);
            if (cep.length >= 6) {
                e.target.value = cep.replace(/(\d{5})(\d{1,3})/, '$1-$2');
            } else if (cep.length > 5) {
                e.target.value = cep.replace(/(\d{5})(\d+)/, '$1-$2');
            } else {
                e.target.value = cep;
            }
        });

        document.getElementById('cep').addEventListener('keyup', function(e) {
            const cep = this.value.replace(/\D/g, '');

            // Verifica se está marcado para retirada
            const isRetirada = document.getElementById('retirada')?.checked;
            if (isRetirada) return; // se for retirada, não calcula a taxa

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.erro) {
                            alert('CEP não encontrado.');
                            return;
                        }

                        document.getElementById('logradouro').value = data.logradouro || '';
                        document.getElementById('cidade').value = data.localidade || '';
                        criarOuAtualizarInput('bairro', data.bairro);
                        criarOuAtualizarInput('uf', data.uf);

                        const numeroInput = document.getElementById('numero');
                        if (numeroInput) {
                            numeroInput.focus();

                            // Escuta quando o número for preenchido
                            numeroInput.addEventListener('blur', function() {
                                const numero = numeroInput.value.trim();
                                if (!numero) return;

                                // Verifica de novo se é retirada
                                if (document.getElementById('retirada')?.checked) return;

                                const enderecoCompleto =
                                    `${data.logradouro}, ${numero}, ${data.localidade}, ${data.uf}`;

                                fetch('/pedidos/calcular-taxa-entrega', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            destino: enderecoCompleto
                                        })
                                    })
                                    .then(async response => {
                                        const result = await response.json();

                                        if (!response.ok) {
                                            alert(result.erro ||
                                                'Erro ao calcular a taxa de entrega.');
                                            return;
                                        }

                                        console.log('Taxa de entrega calculada:', result.taxa);
                                        criarOuAtualizarInput('delivery_fee', result.taxa);
                                        recalcularTotal();
                                    })
                                    .catch(() => {
                                        alert('Erro ao se comunicar com o servidor.');
                                    });
                            }, {
                                once: true
                            });
                        }
                    })
                    .catch(() => {
                        alert('Erro ao buscar o CEP. Tente novamente mais tarde.');
                    });
            }
        });


        function criarOuAtualizarInput(name, valor) {
            let input = document.getElementById(name);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.id = name;
                document.forms[0].appendChild(input);
            }
            input.value = valor || '';
        }
    </script>
    {{-- busca cliente --}}
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
    {{-- busca e adicao de pizza --}}
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
                let preco = parseFloat(produto.price);
                let nome = produto.name;

                if (inputBroto.checked) {
                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }});
                    nome += ' (Broto)';
                }
                inputNome.value = nome;
                inputValor.value = preco.toFixed(2).replace('.', ',');
                inputQtd.value = '1';
                sugestoesBox.classList.add('d-none');

                inputQtd.focus();
                inputQtd.select();
            }


            function buscarProduto(valor) {
                if (valor.length < 2 || valor === ultimoValorBuscado) return;
                ultimoValorBuscado = valor;

                fetch(`/produtos/buscar-pizza-por-nome?nome=${encodeURIComponent(valor)}`)
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

                                // Aplica desconto se a checkbox broto estiver marcada
                                let preco = parseFloat(produto.price);
                                let nome = produto.name;

                                if (inputBroto.checked) {
                                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }});
                                    nome += ' (Broto)';
                                }

                                item.textContent = `${nome} - R$ ${preco.toFixed(2)}`;
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

                // 🧼 Limpa todos os inputs ocultos ANTES de começar a adicionar novos
                const inputContainer = document.getElementById('inputs_ocultos');
                inputContainer.innerHTML = '';

                produtos.forEach((produto, index) => {
                    const valorUnitario = produto.valorUnitario;
                    const precoBorda = produto.precoBorda || 0;
                    const valorComBorda = valorUnitario + precoBorda;
                    const valorTotal = produto.total.toFixed(2);

                    const nomeBordaFormatado = precoBorda > 0 ? produto.borda : 'Tradicional';
                    const observacaoFormatada = produto.observacao ? `Obs: ${produto.observacao}` : '';
                    const nomeCompleto = `${produto.nome} c/ borda ${nomeBordaFormatado}`;

                    const row = document.createElement('div');
                    row.classList.add('row', 'g-2', 'align-items-start', 'mb-2');

                    row.innerHTML = `
            <div class="col-md-10">
                <strong>${nomeCompleto}</strong> (${produto.quantidade}x) - R$ ${valorTotal}<br>
                <small class="text-muted">${observacaoFormatada}</small>
                <input type="hidden" class="valor-produto" value="${valorTotal}">
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <button class="btn btn-danger btn-sm" onclick="removerProduto(${index})">Remover</button>
            </div>
        `;

                    listaProdutos.appendChild(row);

                    // ✅ Cria os inputs ocultos corretamente
                    const inputGroup = document.createElement('div');
                    inputGroup.id = `produto_unidade_${index}`;

                    inputGroup.innerHTML = `
                <input type="hidden" name="produtos[${index}][nome]" value="${produto.nome}">
                <input type="hidden" name="produtos[${index}][quantidade]" value="${produto.quantidade}">
                <input type="hidden" name="produtos[${index}][valor]" value="${produto.valorUnitario}">
                <input type="hidden" name="produtos[${index}][borda]" value="${produto.borda}">
                <input type="hidden" name="produtos[${index}][preco_borda]" value="${produto.precoBorda}">
                <input type="hidden" name="produtos[${index}][total]" value="${produto.total}">
                <input type="hidden" name="produtos[${index}][observacao]" value="${produto.observacao}">
            `;

                    inputContainer.appendChild(inputGroup);
                });

                recalcularTotal();
            }


            // Função global para remover produto da lista
            window.removerProduto = function(index) {
                produtos.splice(index, 1);
                document.getElementById('produto_unidade_' + index)?.remove();
                atualizarListaProdutos();
            };
        });
    </script>
    {{-- busca e adicao de pizza meai a meia --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let produtosMeia = [];
            const listaProdutosMeia = document.getElementById('lista_pizzas_meia');

            const inputMeia1 = document.getElementById('meia1_nome');
            const inputMeia2 = document.getElementById('meia2_nome');
            const inputQtd = document.getElementById('meia_qtd');
            const inputValor = document.getElementById('meia_valor');
            const bordaSelect = document.getElementById('meia_borda');
            const inputObs = document.getElementById('meia_obs');
            const listaProdutos = document.getElementById('lista_produtos');

            const sugestaoMeia1 = document.getElementById('sugestoes-meia1');
            const sugestaoMeia2 = document.getElementById('sugestoes-meia2');

            let valores = {
                meia1: 0,
                meia2: 0
            };
            let nomes = {
                meia1: '',
                meia2: ''
            };

            function atualizarValorSeMaior() {
                const maior = Math.max(valores.meia1, valores.meia2);
                const atual = parseFloat(inputValor.value.replace(',', '.')) || 0;
                if (maior > atual) {
                    inputValor.value = maior.toFixed(2).replace('.', ',');
                }
            }

            function adicionarPizzaMeia() {
                const nome1 = nomes.meia1;
                const nome2 = nomes.meia2;
                const quantidade = parseFloat(inputQtd.value) || 1;
                const precoBorda = parseFloat(bordaSelect.selectedOptions[0]?.dataset.preco || 0);
                const nomeBorda = bordaSelect.value;
                const observacao = inputObs.value.trim();
                const valorUnitario = parseFloat(inputValor.value.replace(',', '.')) || 0;
                const total = quantidade * (valorUnitario + precoBorda);

                if (!nome1 || !nome2 || valorUnitario === 0) {
                    alert('Preencha os dois sabores corretamente.');
                    return;
                }

                produtosMeia.push({
                    nome: `Meia (${nome1})/(${nome2})`,
                    quantidade,
                    valorUnitario,
                    borda: nomeBorda,
                    precoBorda,
                    total,
                    observacao
                });

                atualizarListaProdutosMeia();

                // Limpa
                inputMeia1.value = '';
                inputMeia2.value = '';
                inputQtd.value = '1';
                inputValor.value = '';
                bordaSelect.selectedIndex = 0;
                inputObs.value = '';
                sugestaoMeia1.classList.add('d-none');
                sugestaoMeia2.classList.add('d-none');
                valores = {
                    meia1: 0,
                    meia2: 0
                };
                nomes = {
                    meia1: '',
                    meia2: ''
                };
                inputMeia1.focus();
            }

            let indexSelecionadoMeia1 = -1;
            let sugestoesMeia1 = [];

            let indexSelecionadoMeia2 = -1;
            let sugestoesMeia2 = [];

            function buscarPizza(nome, callback, box) {
                if (nome.length < 2) return;

                const checkboxBroto = document.getElementById('meia_broto');

                fetch(`/produtos/buscar-pizza-por-nome?nome=${encodeURIComponent(nome)}`)
                    .then(res => res.json())
                    .then(data => {
                        box.innerHTML = '';

                        // Navegação por teclado
                        if (box === sugestaoMeia1) {
                            sugestoesMeia1 = data;
                            indexSelecionadoMeia1 = -1;
                        } else if (box === sugestaoMeia2) {
                            sugestoesMeia2 = data;
                            indexSelecionadoMeia2 = -1;
                        }

                        if (data.length) {
                            data.forEach((produto, index) => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.classList.add('list-group-item', 'list-group-item-action');

                                // Aplica desconto e renomeia se checkbox estiver marcada
                                let preco = parseFloat(produto.price);
                                let nomeProduto = produto.name;

                                if (checkboxBroto?.checked) {
                                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }});
                                    nomeProduto += ' (Broto)';
                                }

                                item.textContent = `${nomeProduto} - R$ ${preco.toFixed(2)}`;
                                item.dataset.index = index;

                                item.addEventListener('click', function(e) {
                                    e.preventDefault();

                                    const produtoModificado = {
                                        ...produto,
                                        name: nomeProduto,
                                        price: preco
                                    };

                                    callback(produtoModificado);
                                    box.classList.add('d-none');
                                });

                                box.appendChild(item);
                            });

                            box.classList.remove('d-none');
                        } else {
                            box.classList.add('d-none');
                        }
                    });
            }


            inputMeia1.addEventListener('keydown', function(e) {
                const itens = sugestaoMeia1.querySelectorAll('.list-group-item');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (indexSelecionadoMeia1 < itens.length - 1) {
                        indexSelecionadoMeia1++;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionadoMeia1].classList.add('active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (indexSelecionadoMeia1 > 0) {
                        indexSelecionadoMeia1--;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionadoMeia1].classList.add('active');
                    }
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (indexSelecionadoMeia1 >= 0 && sugestoesMeia1[indexSelecionadoMeia1]) {
                        preencherMeia1(sugestoesMeia1[indexSelecionadoMeia1]);
                        sugestaoMeia1.classList.add('d-none');
                    }
                }
            });





            inputMeia2.addEventListener('keydown', function(e) {
                const itens = sugestaoMeia2.querySelectorAll('.list-group-item');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (indexSelecionadoMeia2 < itens.length - 1) {
                        indexSelecionadoMeia2++;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionadoMeia2].classList.add('active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (indexSelecionadoMeia2 > 0) {
                        indexSelecionadoMeia2--;
                        itens.forEach(item => item.classList.remove('active'));
                        itens[indexSelecionadoMeia2].classList.add('active');
                    }
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (indexSelecionadoMeia2 >= 0 && sugestoesMeia2[indexSelecionadoMeia2]) {
                        preencherMeia2(sugestoesMeia2[indexSelecionadoMeia2]);
                        sugestaoMeia2.classList.add('d-none');
                    }
                }
            });




            function preencherMeia1(produto) {
                let preco = parseFloat(produto.price);
                let nome = produto.name;

                if (checkboxBroto.checked) {
                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }});
                    nome += ' (Broto)';
                }

                inputMeia1.value = nome;
                valores.meia1 = preco;
                nomes.meia1 = nome;
                atualizarValorSeMaior();
                inputMeia2.focus();
            }


            function preencherMeia2(produto) {
                let preco = parseFloat(produto.price);
                let nome = produto.name;

                if (checkboxBroto.checked) {
                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }});
                    nome += ' (Broto)';
                }

                inputMeia2.value = nome;
                valores.meia2 = preco;
                nomes.meia2 = nome;
                atualizarValorSeMaior();
                inputQtd.focus();
                inputQtd.select();
            }


            // Navegação com Enter
            inputQtd.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    inputValor.focus();
                }
            });
            inputValor.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    bordaSelect.focus();
                }
            });
            bordaSelect.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    inputObs.focus();
                }
            });
            inputObs.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    adicionarPizzaMeia();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key.toLowerCase() === 'm') {
                    e.preventDefault();
                    adicionarPizzaMeia();
                }
            });

            function atualizarListaProdutosMeia() {
                listaProdutosMeia.innerHTML = '';
                const inputContainer = document.getElementById('inputs_ocultos_meia');

                // Remove todos os inputs ocultos anteriores de meia
                document.querySelectorAll('[id^="produto_meia_"]').forEach(el => el.remove());

                produtosMeia.forEach((produto, index) => {
                    const total = produto.total.toFixed(2);
                    const nomeBorda = produto.precoBorda > 0 ? produto.borda : 'Tradicional';
                    const observacao = produto.observacao ? `Obs: ${produto.observacao}` : '';
                    const nomeCompleto = `${produto.nome} c/ borda ${nomeBorda}`;

                    // Adiciona a linha visível
                    const row = document.createElement('div');
                    row.classList.add('row', 'g-2', 'align-items-start', 'mb-2');
                    row.innerHTML = `
            <div class="col-md-10">
                <strong>${nomeCompleto}</strong> (${produto.quantidade}x) - R$ ${total}<br>
                <small class="text-muted">${observacao}</small>
                <input type="hidden" class="valor-produto" value="${total}">
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <button class="btn btn-danger btn-sm" onclick="removerProdutoMeia(${index})">Remover</button>
            </div>
        `;
                    listaProdutosMeia.appendChild(row);

                    // Adiciona os inputs ocultos para envio
                    const divId = `produto_meia_${index}`;
                    const inputGroup = document.createElement('div');
                    inputGroup.id = divId;

                    inputGroup.innerHTML = `
                        <input type="hidden" name="pizzas_meia[${index}][nome]" value="${produto.nome}">
                        <input type="hidden" name="pizzas_meia[${index}][quantidade]" value="${produto.quantidade}">
                        <input type="hidden" name="pizzas_meia[${index}][valor]" value="${produto.valorUnitario}">
                        <input type="hidden" name="pizzas_meia[${index}][borda]" value="${produto.borda}">
                        <input type="hidden" name="pizzas_meia[${index}][preco_borda]" value="${produto.precoBorda}">
                        <input type="hidden" name="pizzas_meia[${index}][total]" value="${produto.total}">
                        <input type="hidden" name="pizzas_meia[${index}][observacao]" value="${produto.observacao}">
                    `;
                    inputContainer.appendChild(inputGroup);
                });

                recalcularTotal();
            }


            window.removerProdutoMeia = function(index) {
                produtosMeia.splice(index, 1);
                document.getElementById('produto_meia_' + index)?.remove();
                atualizarListaProdutosMeia();
                recalcularTotal();
            };


            // 🔍 Busca ao digitar
            inputMeia1.addEventListener('input', function() {
                indexSelecionadoMeia1 = -1;
                buscarPizza(inputMeia1.value.trim(), preencherMeia1, sugestaoMeia1);
            });

            inputMeia2.addEventListener('input', function() {
                indexSelecionadoMeia2 = -1;
                buscarPizza(inputMeia2.value.trim(), preencherMeia2, sugestaoMeia2);
            });

        });
    </script>
    {{-- busca e adicao de produto simples --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let produtosSimples = [];

            const inputNome = document.getElementById('produto_simples_nome');
            const inputQtd = document.getElementById('produto_simples_qtd');
            const inputValor = document.getElementById('produto_simples_valor');
            const inputObs = document.getElementById('produto_simples_obs');
            const sugestoesBox = document.getElementById('sugestoes-produtos-simples');
            const listaProdutos = document.getElementById('lista_produtos_simples');

            let sugestoes = [];
            let indexSelecionado = -1;
            let ultimoValorBuscado = '';

            function adicionarProdutoSimples() {
                const nome = inputNome.value.trim();
                const quantidade = parseFloat(inputQtd.value) || 1;
                const valorUnitario = parseFloat(inputValor.value.replace(',', '.')) || 0;
                const observacao = inputObs.value.trim();

                if (!nome || valorUnitario <= 0) {
                    alert('Preencha nome e valor corretamente.');
                    return;
                }

                const total = quantidade * valorUnitario;

                produtosSimples.push({
                    nome,
                    quantidade,
                    valorUnitario,
                    total,
                    observacao
                });

                atualizarListaProdutosSimples();

                inputNome.value = '';
                inputQtd.value = '1';
                inputValor.value = '';
                inputObs.value = '';
                sugestoesBox.classList.add('d-none');

                inputNome.focus();
            }

            function preencherCampos(produto) {
                let preco = parseFloat(produto.price);

                if (produto.is_broto) {
                    preco = Math.max(0, preco - {{ env('PIZZA_BROTO_DISCOUNT', 10) }}); // Aplica desconto configurável se for broto
                }

                inputNome.value = produto.name + (produto.is_broto ? ' (Broto)' : '');
                inputValor.value = preco.toFixed(2).replace('.', ',');
                inputQtd.value = '1';
                sugestoesBox.classList.add('d-none');

                inputQtd.focus();
                inputQtd.select();
            }


            function buscarProduto(valor) {
                if (valor.length < 2 || valor === ultimoValorBuscado) return;
                ultimoValorBuscado = valor;

                fetch(`/produtos/buscar-produto-por-nome?nome=${encodeURIComponent(valor)}`)
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
                    inputObs.focus();
                    inputObs.select();
                }
            });

            inputObs.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    adicionarProdutoSimples();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.altKey && e.key.toLowerCase() === 's') {
                    e.preventDefault();
                    adicionarProdutoSimples();
                }
            });

            function atualizarListaProdutosSimples() {
                listaProdutos.innerHTML = '';
                const inputContainer = document.getElementById('inputs_ocultos_simples');

                // Limpa os inputs ocultos antigos
                document.querySelectorAll('#inputs_ocultos_simples [id^="produto_simples_"]').forEach(el => el
                    .remove());


                produtosSimples.forEach((produto, index) => {
                    const total = produto.total.toFixed(2);
                    const obs = produto.observacao ? `Obs: ${produto.observacao}` : '';

                    // Linha visual
                    const row = document.createElement('div');
                    row.classList.add('row', 'g-2', 'align-items-start', 'mb-2');
                    row.innerHTML = `
            <div class="col-md-10">
                <strong>${produto.nome}</strong> (${produto.quantidade}x) - R$ ${total}<br>
                <small class="text-muted">${obs}</small>
                <input type="hidden" class="valor-produto" value="${produto.total}">
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <button class="btn btn-danger btn-sm" onclick="removerProdutoSimples(${index})">Remover</button>
            </div>
        `;
                    listaProdutos.appendChild(row);

                    // Inputs ocultos
                    const inputGroup = document.createElement('div');
                    inputGroup.id = `produto_simples_${index}`;
                    inputGroup.innerHTML = `
                        <input type="hidden" name="produtos_simples[${index}][nome]" value="${produto.nome}">
                        <input type="hidden" name="produtos_simples[${index}][quantidade]" value="${produto.quantidade}">
                        <input type="hidden" name="produtos_simples[${index}][valor]" value="${produto.valorUnitario}">
                        <input type="hidden" name="produtos_simples[${index}][total]" value="${produto.total}">
                        <input type="hidden" name="produtos_simples[${index}][observacao]" value="${produto.observacao}">
                    `;
                    inputContainer.appendChild(inputGroup);
                });

                recalcularTotal();
            }


            window.removerProdutoSimples = function(index) {
                produtosSimples.splice(index, 1);
                document.getElementById('produto_simples_' + index)?.remove();
                atualizarListaProdutosSimples();
                recalcularTotal();
            };

        });
    </script>
    {{-- recalcula --}}
    <script>
        function parseValor(valor) {
            if (typeof valor === 'number') return valor;
            return parseFloat((valor || "0").toString().replace(',', '.'));
        }

        function recalcularTotal() {
            // Soma dos produtos — vamos atualizar isso depois quando os produtos forem adicionados
            let totalProdutos = 0;

            // Exemplo: cada produto pode ter um input com class "valor-produto"
            document.querySelectorAll('.valor-produto').forEach(input => {
                totalProdutos += parseValor(input.value);
            });

            // Taxa de entrega (hidden input criado dinamicamente)
            let taxaEntrega = parseValor(document.getElementById('delivery_fee')?.value || 0);

            // Total pago (caso você já tenha input ou cálculo futuro)
            let totalPago = parseValor(document.getElementById('valor_pago')?.value || 0);

            // Total final
            let totalGeral = totalProdutos + taxaEntrega;

            // Atualiza DOM
            document.getElementById('total_geral').innerText = totalGeral.toFixed(2);
            document.getElementById('total_falta').innerText = (totalGeral - totalPago).toFixed(2);
            document.getElementById('total_pago').innerText = totalPago.toFixed(2);
            document.getElementById('total_geral_input').value = totalGeral.toFixed(2);

            const troco = totalPago - totalGeral;
            document.getElementById('troco').innerText = troco > 0 ? `Troco: R$ ${troco.toFixed(2)}` : '';
        }
        // Exporte globalmente
        window.recalcularTotal = recalcularTotal;
    </script>
    {{-- js forma de pagamentos --}}
    <script>
        let pagamentos = [];

        function parseValor(valor) {
            if (typeof valor === 'number') return valor;
            return parseFloat((valor || "0").toString().replace(',', '.'));
        }

        function atualizarListaPagamentos() {
            const lista = document.getElementById('lista_pagamentos');
            lista.innerHTML = '';

            pagamentos.forEach((pagamento, index) => {
                const row = document.createElement('div');
                row.classList.add('col-md-12');

                row.innerHTML = `
                <div class="d-flex justify-content-between align-items-center border rounded p-2">
                    <div>
                        <strong>${pagamento.forma}</strong>: R$ ${pagamento.valor.toFixed(2)}
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerPagamento(${index})">Remover</button>
                </div>
            `;

                const container = document.getElementById('inputs_pagamentos');
                container.innerHTML = ''; // limpa antes de recriar

                pagamentos.forEach((pagamento, index) => {
                    container.innerHTML += `
                    <input type="hidden" name="pagamentos[${index}][forma]" value="${pagamento.forma}">
                    <input type="hidden" name="pagamentos[${index}][valor]" value="${pagamento.valor}">
                 `;
                });

                lista.appendChild(row);
            });

            recalcularTotal(); // sempre atualiza
        }

        function removerPagamento(index) {
            pagamentos.splice(index, 1);
            atualizarListaPagamentos();
        }

        document.getElementById('btnAdicionarPagamento').addEventListener('click', () => {
            const forma = document.querySelector('input[name="forma_pagamento"]:checked');
            const valor = parseValor(document.getElementById('valor_pagamento').value);

            if (!forma) {
                alert('Selecione uma forma de pagamento.');
                return;
            }

            if (!valor || valor <= 0) {
                alert('Digite um valor válido.');
                return;
            }

            pagamentos.push({
                forma: forma.value,
                valor
            });

            document.getElementById('valor_pagamento').value = '';
            atualizarListaPagamentos();
        });


        // Atualiza totais gerais
        function recalcularTotal() {
            let totalProdutos = 0;
            document.querySelectorAll('.valor-produto').forEach(input => {
                totalProdutos += parseValor(input.value);
            });

            let taxaEntrega = parseValor(document.getElementById('delivery_fee')?.value || 0);
            let totalPago = pagamentos.reduce((sum, p) => sum + p.valor, 0);

            let totalGeral = totalProdutos + taxaEntrega;
            let falta = Math.max(totalGeral - totalPago, 0);
            document.getElementById('total_falta').innerText = falta.toFixed(2);


            document.getElementById('total_geral').innerText = totalGeral.toFixed(2);
            document.getElementById('total_falta').innerText = falta.toFixed(2);
            document.getElementById('total_pago').innerText = totalPago.toFixed(2);
            document.getElementById('total_geral_input').value = totalGeral.toFixed(2);

            const troco = totalPago - totalGeral;
            document.getElementById('troco').innerText = troco > 0 ? `Troco: R$ ${troco.toFixed(2)}` : '';

            // 👇 Atualiza automaticamente o valor do próximo pagamento
            const valorInput = document.getElementById('valor_pagamento');
            if (falta > 0 && valorInput) {
                valorInput.value = falta.toFixed(2);
            }
        }

        // Deixa acessível no global
        window.recalcularTotal = recalcularTotal;
    </script>
    {{-- js para nao salvar o form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form'); // Se quiser, use um ID tipo '#formulario'

            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    if (!e.altKey) {
                        e.preventDefault(); // Bloqueia submit no Enter comum
                    } else {
                        // Alt + Enter = submit forçado
                        form.submit();
                    }
                }
            });
        });
    </script>
    {{-- atalhos --}}
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.altKey) {
                switch (e.key) {
                    case '1':
                        e.preventDefault();
                        document.getElementById('produto_unidade_nome')?.focus();
                        break;
                    case '2':
                        e.preventDefault();
                        document.getElementById('meia1_nome')?.focus();
                        break;
                    case '3':
                        e.preventDefault();
                        document.getElementById('produto_simples_nome')?.focus();
                        break;
                }
            }
        });
    </script>
@endsection
