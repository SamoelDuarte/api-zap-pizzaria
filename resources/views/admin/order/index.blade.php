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
                                            <td>{{ $order->customer_name }} <br> 📞 {{ $order->customer_phone }}</td>
                                            <td>R$ {{ $order->total_geral }}</td>
                                            <td>R$ {{ $order->delivery_fee }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{!! $order->formas_pagamento !!}</td>
                                            <td>{{ $order->data }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm {{ $order->motoboy_name ? 'btn-success btn-alterar-motoboy' : 'btn-warning btn-add-motoboy' }}"
                                                    data-id="{{ $order->id }}">
                                                    {{ $order->motoboy_name ? '🛵 ' . $order->motoboy_name : 'Adicionar Motoboy' }}
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
@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/js/order/index.js') }}"></script>
@endsection
