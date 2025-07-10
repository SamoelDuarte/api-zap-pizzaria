@extends('admin.layout.app')

@section('content')
    <div class="container">
        <h3>{{ isset($motoboy) ? 'Editar' : 'Cadastrar' }} Motoboy</h3>

        <form action="{{ isset($motoboy) ? route('admin.motoboy.update', $motoboy) : route('admin.motoboy.store') }}" method="POST">
            @csrf
            @if(isset($motoboy)) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" name="name" class="form-control title-case" value="{{ $motoboy->name ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefone</label>
                <input type="text" name="phone" class="form-control" value="{{ $motoboy->phone ?? '' }}">
            </div>

            <button class="btn btn-success mt-2">Salvar</button>
        </form>
    </div>
@endsection
