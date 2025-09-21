@extends('admin.layout.app')

@section('css')
    <style>
        /* Adiciona um estilo personalizado aqui se necessário */
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="page-header-content py-3">
        <ol class="breadcrumb mb-0 mt-4">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Config</li>
        </ol>
    </div>
    <form method="POST" action="{{ route('admin.config.update') }}">
        <div class="mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Configurações</div>

                        <div class="card-body">

                            @csrf
                            @method('PUT')

                            {{-- <div class="form-group">
                                <label for="motoboy_fone">Motoboy Telephone:</label>
                                <input type="text" id="motoboy_fone" name="motoboy_fone"
                                    value="{{ $config->motoboy_fone }}" placeholder="(11) 9 1234-1234" class="form-control">
                            </div> --}}

                            {{-- <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="status" name="status" class="form-check-input"
                                        {{ $config->status ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label">Enviar Para Motoquiro</label>
                                </div>
                            </div> --}}

                            <div class="card-header">tempo de Entrega</div>
                            <div class="form-group row">
                               
                                <div>
                                    <label for="hora">Horas:</label>
                                    <input type="number" id="hora" name="hora" value="{{ intdiv($config->minuts, 60) }}" class="form-control">
                                </div>
                               <div>
                                <label for="minutos">Minutos:</label>
                                <input type="number" id="minutos" name="minutos" value="{{ $config->minuts % 60 }}" class="form-control">
                               </div>
                            </div>

                            <!-- Informações da Pizzaria -->
                            <div class="card-header mt-3">📍 Informações da Pizzaria</div>
                            
                            <div class="form-group">
                                <label for="nome_pizzaria">🍕 Nome da Pizzaria:</label>
                                <input type="text" id="nome_pizzaria" name="nome_pizzaria" 
                                    value="{{ $config->nome_pizzaria }}" 
                                    placeholder="Nome da Pizzaria" 
                                    class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="telefone">📞 Telefone da Pizzaria:</label>
                                <input type="text" id="telefone" name="telefone" 
                                    value="{{ $config->telefone }}" 
                                    placeholder="(11) 9 1234-5678" 
                                    class="form-control">
                                <small class="text-muted">Digite apenas os números, o código do país (55) será adicionado automaticamente</small>
                            </div>

                            <div class="form-group">
                                <label for="cep">📮 CEP:</label>
                                <input type="text" id="cep" name="cep" 
                                    value="{{ $config->cep }}" 
                                    placeholder="00000-000" 
                                    class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="endereco">🏠 Endereço:</label>
                                <input type="text" id="endereco" name="endereco" 
                                    value="{{ $config->endereco }}" 
                                    placeholder="Rua/Avenida" 
                                    class="form-control" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numero">🔢 Número:</label>
                                        <input type="text" id="numero" name="numero" 
                                            value="{{ $config->numero }}" 
                                            placeholder="123" 
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="complemento">🏢 Complemento:</label>
                                        <input type="text" id="complemento" name="complemento" 
                                            value="{{ $config->complemento }}" 
                                            placeholder="Apto 101, Bloco A" 
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bairro">🏘️ Bairro:</label>
                                <input type="text" id="bairro" name="bairro" 
                                    value="{{ $config->bairro }}" 
                                    placeholder="Centro" 
                                    class="form-control" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="cidade">🏙️ Cidade:</label>
                                        <input type="text" id="cidade" name="cidade" 
                                            value="{{ $config->cidade }}" 
                                            placeholder="São Paulo" 
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado">🗺️ Estado:</label>
                                        <input type="text" id="estado" name="estado" 
                                            value="{{ $config->estado }}" 
                                            placeholder="SP" 
                                            class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            
                          

                            {{-- <div class="form-group d-none">
                                <div class="form-check">
                                    <input type="checkbox" id="chatbot" name="chatbot" class="form-check-input"
                                        {{ $config->chatbot ? 'checked' : '' }}>
                                    <label for="chatbot" class="form-check-label">Atendimento Automatico</label>
                                </div>
                            </div> --}}

                            {{-- <div class="form-group d-none" >
                              <label for="">Mensagem de Resposta </label>
                              <textarea class="form-control" name="resposta" id="" rows="3">{{ $config->resposta }}</textarea>
                            </div> --}}
                          

                        </div>
                          <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                     
                </div>
               
            </div>
            {{-- <div class="row">
                @php
                    $days = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];
                @endphp

                @foreach ($days as $index => $day)
                    @php
                        $slot = $availability->firstWhere('day_of_week', $day);
                    @endphp

                    <div class="col">
                        <div class="card">
                            <div class="card-header" id="{{ 'header-' . $index }}"
                                style="background-color: {{ $slot ? '#e2ffee' : '#ffe5e2' }}">
                                {{ ucfirst($day) }}</div>
                            <div class="card-body">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                        id="{{ 'switch-' . $index }}"
                                        name="days[{{ $day }}][active]"
                                        onchange="toggleStatus(this, '{{ 'header-' . $index }}')"
                                        {{ $slot ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="{{ 'switch-' . $index }}">{{ $slot ? 'Ativo' : 'Inativo' }}</label>
                                </div>
                                <div class="form-group">
                                    <label>Hora de início:</label>
                                    <input type="time" class="form-control"
                                        name="days[{{ $day }}][start_time]"
                                        value="{{ $slot->start_time ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Hora de término:</label>
                                    <input type="time" class="form-control"
                                        name="days[{{ $day }}][end_time]"
                                        value="{{ $slot->end_time ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> --}}
            
        </div>
      
    </form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            // Máscaras
            $('#motoboy_fone').mask('(00) 0 0000-0000');
            $('#telefone').mask('(00) 0 0000-0000');
            $('#cep').mask('00000-000');

            // Busca CEP
            $('#cep').on('blur', function() {
                var cep = $(this).val().replace(/\D/g, '');
                
                if (cep.length === 8) {
                    $.ajax({
                        url: `https://viacep.com.br/ws/${cep}/json/`,
                        method: 'GET',
                        success: function(data) {
                            if (!data.erro) {
                                $('#endereco').val(data.logradouro);
                                $('#bairro').val(data.bairro);
                                $('#cidade').val(data.localidade);
                                $('#estado').val(data.uf);
                                $('#numero').focus();
                            } else {
                                alert('CEP não encontrado!');
                            }
                        },
                        error: function() {
                            alert('Erro ao buscar CEP!');
                        }
                    });
                }
            });
        });

        function toggleStatus(element, headerId) {
            var header = document.getElementById(headerId);
            if (element.checked) {
                element.nextElementSibling.textContent = 'Ativo';
                header.style.backgroundColor = '#e2ffee';
            } else {
                element.nextElementSibling.textContent = 'Inativo';
                header.style.backgroundColor = '#ffe5e2';
            }
        }
    </script>
@endsection
