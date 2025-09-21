<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Área do Forneiro - {{ $globalConfig->nome_pizzaria ?? 'Integra Pizzaria' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-utensils text-warning"></i> Área do Forneiro
        </h1>
        <div class="d-flex">
            <span class="badge badge-info mr-2">
                <span id="total-pedidos">{{ $pedidos->count() }}</span> pedidos pendentes
            </span>
            <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i> Atualizar
            </button>
        </div>
    </div>

    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .forneiro-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0;
            margin: 0;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .container-fluid {
            padding: 0;
            max-width: 100%;
        }
        .pedido-card {
            background: white;
            border-radius: 10px;
            margin: 10px 0;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        
        .pedido-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .pedido-compacto {
            cursor: pointer;
            user-select: none;
        }
        
        .pedido-detalhes {
            display: none;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #f8f9fa;
            animation: slideDown 0.3s ease;
        }
        
        .pedido-detalhes.show {
            display: block;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-expand {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
            padding: 5px;
        }
        
        .btn-expand:hover {
            color: #495057;
        }
        
        .resumo-pedido {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .info-principal {
            flex: 1;
            min-width: 200px;
        }
        
        .acoes-rapidas {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .btn-forneiro {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 2px;
            min-width: 100px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .btn-feito {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        
        .btn-feito:hover {
            background: linear-gradient(45deg, #218838, #1ea080);
            transform: scale(1.05);
            color: white;
        }
        
        .btn-motoboy {
            background: linear-gradient(45deg, #fd7e14, #ffc107);
            border: none;
            color: white;
        }
        
        .btn-motoboy:hover {
            background: linear-gradient(45deg, #e8690b, #e0a800);
            transform: scale(1.05);
            color: white;
        }
        
        .btn-motoboy.atribuido {
            background: linear-gradient(45deg, #6f42c1, #e83e8c);
        }
        
        .bairro-header {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            color: #5a5c69;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-left: 4px solid #4e73df;
        }
        
        .cliente-info, .produtos-lista, .pagamento-info {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .produto-item {
            padding: 8px 0;
            border-bottom: 1px solid rgba(78, 115, 223, 0.1);
        }
        
        .produto-item:last-child {
            border-bottom: none;
        }
        
        .valor-total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }
        
        .produtos-resumo {
            background: rgba(248, 249, 252, 0.8);
            border-radius: 6px;
            padding: 10px;
            margin: 10px 0;
            border-left: 3px solid #4e73df;
        }
        
        .produto-resumo-item {
            margin-bottom: 6px;
            padding-bottom: 4px;
            line-height: 1.4;
        }
        
        .produto-resumo-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .produto-resumo-item:not(:last-child) {
            border-bottom: 1px solid rgba(78, 115, 223, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #e3e6f0;
        }
        
        .loading {
            pointer-events: none;
            opacity: 0.6;
        }
        
        @media (max-width: 768px) {
            .btn-forneiro {
                min-width: 80px;
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .resumo-pedido {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .acoes-rapidas {
                align-self: flex-end;
            }
        }
    </style>

    <!-- Lista de Pedidos -->
    <div id="lista-pedidos">
        @if($pedidos->count() > 0)
            @php
                $bairroAtual = '';
            @endphp
            
            @foreach($pedidos as $pedido)
                @php
                    $bairroPedido = strtolower($pedido->customer->neighborhood ?? 'sem bairro');
                @endphp
                
                @if($bairroPedido !== $bairroAtual)
                    @php $bairroAtual = $bairroPedido; @endphp
                    <div class="bairro-header">
                        <i class="fas fa-map-marker-alt"></i> {{ ucfirst($bairroPedido) }}
                    </div>
                @endif
                
                <div class="pedido-card" data-id="{{ $pedido->id }}">
                    <!-- Resumo Compacto -->
                    <div class="pedido-compacto" onclick="toggleDetalhes({{ $pedido->id }})">
                        <div class="resumo-pedido">
                            <div class="info-principal">
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="text-primary">#{{ $pedido->id }}</strong>
                                    <span class="badge badge-secondary ml-2">{{ $pedido->created_at->format('H:i') }}</span>
                                    <span class="badge badge-info ml-1">{{ ucfirst($bairroPedido) }}</span>
                                </div>
                                <div class="text-dark mb-1">
                                    <i class="fas fa-user text-muted"></i> {{ $pedido->customer->name }}
                                </div>
                                
                                <!-- Lista de Produtos no Resumo -->
                                <div class="produtos-resumo mb-2">
                                    @foreach($pedido->items as $item)
                                        <div class="produto-resumo-item small d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <span class="text-dark fw-bold">{{ $item->name }}</span>
                                                @if($item->quantity > 1)
                                                    <span class="badge badge-primary badge-sm ml-1">x{{ $item->quantity }}</span>
                                                @endif
                                                @if($item->observation)
                                                    <div class="text-warning mt-1" style="font-size: 0.75rem;">
                                                        <i class="fas fa-comment"></i> {{ $item->observation }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-success font-weight-bold ml-2">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="small text-muted">
                                    {{ $pedido->items->count() }} {{ $pedido->items->count() > 1 ? 'itens' : 'item' }} • 
                                    R$ {{ number_format($pedido->total_geral, 2, ',', '.') }}
                                </div>
                            </div>
                            <div class="acoes-rapidas">
                                <button class="btn btn-forneiro btn-feito" onclick="event.stopPropagation(); marcarComoFeito({{ $pedido->id }})">
                                    <i class="fas fa-check-circle"></i> Pronto
                                </button>
                                
                                <button class="btn btn-forneiro btn-motoboy {{ $pedido->motoboy ? 'atribuido' : '' }}" 
                                        onclick="event.stopPropagation(); abrirModalMotoboy({{ $pedido->id }})">
                                    <i class="fas fa-motorcycle"></i> 
                                    {{ $pedido->motoboy ? $pedido->motoboy->name : 'Motoboy' }}
                                </button>
                                
                                <button class="btn-expand" title="Ver detalhes">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detalhes Expandidos -->
                    <div class="pedido-detalhes" id="detalhes-{{ $pedido->id }}">
                        <!-- Info do Cliente -->
                        <div class="cliente-info">
                            <h6><i class="fas fa-user text-info"></i> {{ $pedido->customer->name }}</h6>
                            <p class="mb-1"><i class="fas fa-phone text-success"></i> {{ $pedido->customer->phone }}</p>
                            <p class="mb-0"><i class="fas fa-map-marker-alt text-danger"></i> {{ $pedido->customer->location }}</p>
                        </div>
                        
                        <!-- Lista de Produtos Detalhada -->
                        <div class="produtos-lista">
                            <h6><i class="fas fa-pizza-slice text-warning"></i> Detalhes dos Produtos:</h6>
                            @foreach($pedido->items as $item)
                                <div class="produto-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $item->name }}</strong>
                                            @if($item->quantity > 1)
                                                <span class="badge badge-primary">x{{ $item->quantity }}</span>
                                            @endif
                                            @if($item->observation)
                                                <div class="small text-warning mt-1">
                                                    <i class="fas fa-comment"></i> {{ $item->observation }}
                                                </div>
                                            @endif
                                            @if($item->size)
                                                <div class="small text-info mt-1">
                                                    <i class="fas fa-expand-arrows-alt"></i> {{ $item->size }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-success font-weight-bold">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Info de Pagamento -->
                        <div class="pagamento-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-credit-card text-primary"></i> Pagamento:</h6>
                                    @foreach($pedido->payments as $payment)
                                        <small>{{ $payment->paymentMethod->name }}: R$ {{ number_format($payment->amount, 2, ',', '.') }}</small><br>
                                    @endforeach
                                    @if($pedido->change_for)
                                        <small class="text-warning"><i class="fas fa-money-bill"></i> Troco: R$ {{ number_format($pedido->change_for, 2, ',', '.') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="valor-total">
                                        Total: R$ {{ number_format($pedido->total_geral, 2, ',', '.') }}
                                    </div>
                                    @if($pedido->delivery_fee > 0)
                                        <small class="text-muted">Taxa entrega: R$ {{ number_format($pedido->delivery_fee, 2, ',', '.') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h4>Nenhum pedido pendente</h4>
                        <p>Todos os pedidos foram processados!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Motoboy -->
<div class="modal fade" id="modalMotoboy" tabindex="-1" aria-labelledby="modalMotoboyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMotoboyLabel"><i class="fas fa-motorcycle text-warning"></i> Selecionar Motoboy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="lista-motoboys">
                    <!-- Carregado via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let pedidoIdSelecionado = null;

// Configurar token CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log('CSRF Token:', csrfToken);

/**
 * Toggle detalhes do pedido
 */
function toggleDetalhes(pedidoId) {
    const detalhes = document.getElementById(`detalhes-${pedidoId}`);
    const btnExpand = detalhes.previousElementSibling.querySelector('.btn-expand i');
    
    if (detalhes.classList.contains('show')) {
        detalhes.classList.remove('show');
        btnExpand.className = 'fas fa-plus';
    } else {
        detalhes.classList.add('show');
        btnExpand.className = 'fas fa-minus';
    }
}

/**
 * Marca pedido como feito
 */
function marcarComoFeito(pedidoId) {
    if (!confirm('Confirmar que o pedido está pronto?')) return;
    
    console.log('Marcando pedido como feito:', pedidoId);
    
    const card = document.querySelector(`[data-id="${pedidoId}"]`);
    card.classList.add('loading');
    
    fetch(`{{ route('admin.forneiro.marcar-feito', '') }}/${pedidoId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Remove o card com animação
            card.style.transition = 'all 0.5s ease';
            card.style.transform = 'translateX(100%)';
            card.style.opacity = '0';
            
            setTimeout(() => {
                card.remove();
                atualizarContador();
                verificarListaVazia();
            }, 500);
            
            mostrarAlerta('success', data.message || 'Pedido marcado como pronto!');
        } else {
            card.classList.remove('loading');
            mostrarAlerta('error', data.message || 'Erro ao marcar pedido como pronto');
        }
    })
    .catch(error => {
        card.classList.remove('loading');
        console.error('Erro completo:', error);
        mostrarAlerta('error', `Erro ao processar solicitação: ${error.message}`);
    });
}

/**
 * Abre modal para selecionar motoboy
 */
function abrirModalMotoboy(pedidoId) {
    console.log('=== INICIANDO abrirModalMotoboy ===');
    console.log('Pedido ID:', pedidoId);
    console.log('Bootstrap object:', typeof bootstrap);
    
    pedidoIdSelecionado = pedidoId;
    carregarMotoboys();
    
    try {
        // Usar Bootstrap 5 nativo ao invés de jQuery
        const modalElement = document.getElementById('modalMotoboy');
        console.log('Modal element:', modalElement);
        
        if (!modalElement) {
            console.error('Modal element não encontrado!');
            return;
        }
        
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap não está carregado!');
            return;
        }
        
        const modal = new bootstrap.Modal(modalElement);
        console.log('Modal object criado:', modal);
        
        modal.show();
        console.log('Modal.show() chamado');
    } catch (error) {
        console.error('Erro ao abrir modal:', error);
    }
}

/**
 * Carrega lista de motoboys
 */
function carregarMotoboys() {
    console.log('Iniciando carregamento de motoboys...');
    document.getElementById('lista-motoboys').innerHTML = 
        '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>';
    
    const url = '{{ route('admin.forneiro.motoboys') }}';
    console.log('URL da requisição:', url);
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => {
            console.log('Motoboys response status:', response.status);
            console.log('Motoboys response headers:', response.headers);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(motoboys => {
            console.log('Motoboys data recebidos:', motoboys);
            let html = '';
            motoboys.forEach(motoboy => {
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${motoboy.name}</strong><br>
                            <small class="text-muted">${motoboy.phone || 'Sem telefone'}</small>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="atribuirMotoboy(${motoboy.id}, '${motoboy.name}')">
                            <i class="fas fa-check"></i> Selecionar
                        </button>
                    </div>
                `;
            });
            
            if (html === '') {
                html = '<div class="text-center text-muted">Nenhum motoboy cadastrado</div>';
            } else {
                html = '<div class="list-group">' + html + '</div>';
            }
            
            document.getElementById('lista-motoboys').innerHTML = html;
            console.log('Lista de motoboys atualizada no DOM');
        })
        .catch(error => {
            console.error('Erro ao carregar motoboys:', error);
            document.getElementById('lista-motoboys').innerHTML = 
                `<div class="text-center text-danger">Erro ao carregar motoboys: ${error.message}</div>`;
        });
}

/**
 * Atribui motoboy ao pedido
 */
function atribuirMotoboy(motoboyId, motoboyNome) {
    console.log('Atribuindo motoboy:', motoboyId, motoboyNome, 'ao pedido:', pedidoIdSelecionado);
    
    fetch('{{ route('admin.forneiro.atribuir-motoboy') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_id: pedidoIdSelecionado,
            motoboy_id: motoboyId
        })
    })
    .then(response => {
        console.log('Atribuir motoboy response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Atribuir motoboy response data:', data);
        if (data.success) {
            // Atualiza o botão do motoboy no card
            const card = document.querySelector(`[data-id="${pedidoIdSelecionado}"]`);
            const btnMotoboy = card.querySelector('.btn-motoboy');
            
            btnMotoboy.innerHTML = `<i class="fas fa-motorcycle"></i> ${motoboyNome}`;
            btnMotoboy.classList.add('atribuido');
            
            // Fecha o modal usando Bootstrap 5 nativo
            const modalElement = document.getElementById('modalMotoboy');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            
            mostrarAlerta('success', data.message || 'Motoboy atribuído com sucesso!');
        } else {
            mostrarAlerta('error', data.message || 'Erro ao atribuir motoboy');
        }
    })
    .catch(error => {
        console.error('Erro ao atribuir motoboy:', error);
        mostrarAlerta('error', `Erro ao processar solicitação: ${error.message}`);
    });
}

/**
 * Atualiza contador de pedidos
 */
function atualizarContador() {
    const total = document.querySelectorAll('.pedido-card').length;
    document.getElementById('total-pedidos').textContent = total;
}

/**
 * Verifica se a lista está vazia e mostra mensagem
 */
function verificarListaVazia() {
    const cards = document.querySelectorAll('.pedido-card');
    if (cards.length === 0) {
        document.getElementById('lista-pedidos').innerHTML = `
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h4>Nenhum pedido pendente</h4>
                        <p>Todos os pedidos foram processados!</p>
                    </div>
                </div>
            </div>
        `;
    }
}

/**
 * Mostra alerta de feedback
 */
function mostrarAlerta(tipo, mensagem) {
    const tipoBootstrap = tipo === 'success' ? 'success' : 'danger';
    const icone = tipo === 'success' ? 'check-circle' : 'exclamation-triangle';
    
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipoBootstrap} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        <i class="fas fa-${icone}"></i> ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alerta);
    
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}

// Auto-refresh da página a cada 2 minutos
setInterval(() => {
    location.reload();
}, 120000);
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>