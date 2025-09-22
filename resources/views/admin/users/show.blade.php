@extends('admin.layout.app')

@section('title', 'Visualizar Usuário')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user text-primary"></i> Visualizar Usuário
        </h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Informações do Usuário -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Dados Pessoais
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($user->picture)
                        <img src="{{ asset($user->picture) }}" class="rounded-circle mb-3" width="100" height="100">
                    @else
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                            <span class="text-white h2">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    
                    @if($user->role == 'admin')
                        <span class="badge bg-danger mb-3">Administrador</span>
                    @else
                        <span class="badge bg-secondary mb-3">Usuário</span>
                    @endif
                    
                    @if(session('admin_id') == $user->id)
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> Este é o seu perfil
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Status da Conta:</span>
                        @if($user->active)
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-secondary">Inativo</span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Função:</span>
                        @if($user->role == 'admin')
                            <span class="badge bg-danger">Administrador</span>
                        @else
                            <span class="badge bg-secondary">Usuário</span>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <!-- Ações rápidas -->
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-outline-secondary' : 'btn-outline-success' }} w-100">
                                <i class="fas fa-{{ $user->active ? 'ban' : 'check' }}"></i> 
                                {{ $user->active ? 'Desativar' : 'Ativar' }} Usuário
                            </button>
                        </form>
                        
                        @if(session('admin_id') != $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Tem certeza que deseja deletar este usuário?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fas fa-trash"></i> Deletar Usuário
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Informações de Contato -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-address-card"></i> Informações de Contato
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Telefone:</strong>
                            <p class="text-muted">{{ $user->phone ?: 'Não informado' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog"></i> Informações do Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>ID do Usuário:</strong>
                            <p class="text-muted">#{{ $user->id }}</p>
                            
                            <strong>Data de Cadastro:</strong>
                            <p class="text-muted">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Última Atualização:</strong>
                            <p class="text-muted">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                            
                            <strong>Tempo de Cadastro:</strong>
                            <p class="text-muted">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Atividades (Placeholder para futuras implementações) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Atividades Recentes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-clock text-muted" style="font-size: 2rem;"></i>
                        <h5 class="text-muted mt-3">Histórico não disponível</h5>
                        <p class="text-muted">O log de atividades será implementado em breve.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection