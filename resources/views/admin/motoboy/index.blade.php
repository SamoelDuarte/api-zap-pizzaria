@extends('admin.layout.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">Lista de Motoboys</h3>

        <a href="{{ route('admin.motoboy.create') }}" class="btn btn-primary mb-3">Adicionar Motoboy</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motoboys as $motoboy)
                    <tr>
                        <td>{{ $motoboy->name }}</td>
                        <td>{{ $motoboy->phone ?? '---' }}</td>
                        <td>
                            <a href="{{ route('admin.motoboy.edit', $motoboy) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.motoboy.destroy', $motoboy) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir motoboy?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
