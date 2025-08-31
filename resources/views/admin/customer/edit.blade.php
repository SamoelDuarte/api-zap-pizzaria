@extends('admin.layout.app')

@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section id="customer-edit">
        <div class="page-header-content py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Editar Cliente</h1>
                <a href="{{ route('admin.customer.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left text-white-50"></i> Voltar
                </a>
            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.customer.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Cliente</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="/clientes/update/{{ $customer->id }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" name="name" class="form-control title-case" id="name"
                                            value="{{ $customer->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="jid">Telefone</label>
                                        <input type="text" name="jid" class="form-control" id="jid"
                                            value="{{ $customer->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tax">Taxa de Entrega Fixa</label>
                                        <input type="number" step="0.01" name="tax" class="form-control" id="tax"
                                            value="{{ $customer->tax }}" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="zipcode">CEP</label>
                                        <input type="text" name="zipcode" class="form-control" id="zipcode"
                                            value="{{ $customer->zipcode }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="public_place">Logradouro</label>
                                        <input type="text" name="public_place" class="form-control" id="public_place"
                                            value="{{ $customer->public_place }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="number">Número</label>
                                        <input type="text" name="number" class="form-control" id="number"
                                            value="{{ $customer->number }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="complement">Complemento</label>
                                        <input type="text" name="complement" class="form-control" id="complement"
                                            value="{{ $customer->complement }}" placeholder="Complemento">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="neighborhood">Bairro</label>
                                        <input type="text" name="neighborhood" class="form-control" id="neighborhood"
                                            value="{{ $customer->neighborhood }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city">Cidade</label>
                                        <input type="text" name="city" class="form-control" id="city"
                                            value="{{ $customer->city }}" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="state">UF</label>
                                        <input type="text" name="state" class="form-control" id="state"
                                            value="{{ $customer->state }}" required>
                                    </div>
                                </div>
                            </div>
                         
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#zipcode').mask('00000-000');

            $('#zipcode').on('input', function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $('#public_place').val(dados.logradouro);
                            $('#neighborhood').val(dados.bairro);
                            $('#city').val(dados.localidade);
                            $('#state').val(dados.uf);
                        } else {
                            alert("CEP não encontrado.");
                        }
                    });
                }
            });

          var phoneMaskBehavior = function(val) {
    var numbers = val.replace(/\D/g, '');
    if(numbers.length > 10) { // 11 para DDD + 9 dígitos
        return '(00) 00000-0000';
    } else {
        return '(00) 0000-00009';
    }
};

var phoneOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(phoneMaskBehavior.apply({}, arguments), options);
    }
};

$('#jid').mask(phoneMaskBehavior, phoneOptions);

        });
    </script>
@endsection
