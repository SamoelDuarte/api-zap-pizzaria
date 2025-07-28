@extends('admin.layout.app')

@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
    <style>
        .status-dot {
            display: inline-block;
            width: 32px;
            height: 30px;
            border-radius: 50%;
            margin-left: 5px;
        }

        .div-circulo {
            display: flex;
            flex-direction: row;
        }
    </style>
@endsection

@section('content')
    <section id="device">
        <div class="page-header-content py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Pedidos</h1>
                <a href="{{ route('admin.order.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus text-white-50"></i> Retirar Pedido
                </a>
            </div>
            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pedidos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" id="filtro-cliente" class="form-control"
                                    placeholder="🔍 Nome ou Telefone">
                            </div>
                            <div class="col-md-2">
                                <select id="filtro-status" class="form-select">
                                    <option value="">🟢 Todos os Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->name }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="filtro-motoboy" class="form-select">
                                    <option value="">🛵 Todos Motoboys</option>
                                    @foreach ($motoboys as $motoboy)
                                        <option value="{{ $motoboy->name }}">{{ $motoboy->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-1">
                                <button id="limpar-filtros" class="btn btn-secondary w-100">Limpar</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.order.index') }}">
                                <div class="input-group">
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') ?? $start->format('Y-m-d') }}">
                                    <span class="input-group-text">a</span>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') ?? $end->format('Y-m-d') }}">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
                            </form>
                        </div>

                        <div class="table-device">
                            <table class="table table-bordered" id="table-order">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Valor Total</th>
                                        <th scope="col">Taxa Entrega</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Pagamento(s)</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Motoboy</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->customer->name }} <br> 📞 {{ $order->customer->phone }}</td>
                                            <td>R$ {{ number_format($order->total_geral, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</td>
                                            <td>
                                                <button class="btn btn-status"
                                                    style="background-color: {{ $order->status->color ?? '#6c757d' }}; color: #fff"
                                                    data-id="{{ $order->id }}"
                                                    data-current-status="{{ $order->status_id }}"
                                                    @if ($order->status->name === 'Cancelado') disabled @endif>
                                                    {{ $order->status->name ?? 'Sem status' }}
                                                </button>
                                            </td>
                                            <td>
                                                {!! $order->payments->map(function ($p) {
                                                        return "{$p->paymentMethod->name} (R$ " . number_format($p->amount, 2, ',', '.') . ')';
                                                    })->implode(', ') !!}
                                                @if ($order->change_for)
                                                    <br><span class="text-danger">Troco: R$
                                                        {{ number_format($order->change_for, 2, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm {{ $order->motoboy ? 'btn-success btn-alterar-motoboy' : 'btn-warning btn-add-motoboy' }} btn-motoboy"
                                                    data-id="{{ $order->id }}"
                                                    data-nome="{{ $order->motoboy->name ?? '' }}"
                                                    @if ($order->status->name === 'Cancelado') disabled @endif>
                                                    {{ $order->motoboy ? '🛵 ' . $order->motoboy->name : 'Adicionar Motoboy' }}
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info btn-ver-pedido"
                                                    data-pedido='@json($order)'>
                                                    Ver
                                                </button>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Visualização do Pedido -->
    <div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalInfoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <!-- Informações do Cliente -->
                    <h5 class="mb-3">👤 Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" id="customer-name" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" id="customer-phone" class="form-control" readonly>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Endereço</label>
                            <textarea id="customer-address" class="form-control" rows="2" readonly></textarea>
                        </div>
                    </div>

                    <!-- Itens do Pedido -->
                    <h5 class="mb-3">📦 Itens do Pedido</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody id="table-items">
                                <!-- Populado via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Motivo do Cancelamento -->
                    <div id="motivo-cancelamento-box" class="mt-3" style="display: none;">
                        <h5 class="text-danger">❌ Motivo do Cancelamento</h5>
                        <p id="motivo-cancelamento" class="text-muted"></p>
                    </div>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    {{-- Se quiser ações como cancelar ou imprimir, coloque aqui --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Escolher Motoboy -->
    <div class="modal fade" id="modalMotoboy" tabindex="-1" role="dialog" aria-labelledby="modalMotoboyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selecionar Motoboy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="lista-motoboys" class="list-group">
                        {{-- Carregado via AJAX --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Status -->
    <div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="modalStatusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStatusLabel">Alterar Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" id="selectStatus">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" data-name="{{ $status->name }}"
                                data-color="{{ $status->color }}">
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="pedidoIdStatus">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnSalvarStatus">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Motivo de Cancelamento -->
    <div class="modal fade" id="modalMotivoCancelamento" tabindex="-1" aria-labelledby="motivoCancelamentoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Motivo do Cancelamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" id="inputMotivoCancelamento" rows="3"
                        placeholder="Descreva o motivo do cancelamento..."></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" id="btnConfirmarCancelamento">Confirmar Cancelamento</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/js/order/index.js') }}"></script>
@endsection
