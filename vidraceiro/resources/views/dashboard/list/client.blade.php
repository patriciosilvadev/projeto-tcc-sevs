@extends('layouts.app')
@section('content')
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card-material custom-card">

            <div class="topo">
                <h4 class="titulo">{{$title}}</h4>
                <a class="btn-link" href="{{ route('clients.create') }}">
                    <button class="btn btn-primary btn-block btn-custom" type="submit">Adicionar</button>
                </a>
            </div>

            @if(session('success'))
                <div class="alerta">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @elseif(session('error'))
                <div class="alerta">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="table-responsive text-dark p-2">
                <table class="table table-hover search-table">
                    <thead>
                    <tr>
                        <th class="noborder" scope="col">Id</th>
                        <th class="noborder" scope="col">Nome</th>
                        <th class="noborder" scope="col">Cpf</th>
                        <th class="noborder" scope="col">Telefone</th>
                        <th class="noborder" scope="col">Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <th scope="row">{{$client->id}}</th>
                            <td>{{$client->nome}}</td>
                            <td>{{$client->cpf}}</td>
                            <td class="telefone">{{$client->telefone}}</td>
                            <td>
                                <a class="btn-link" href="{{ route('clients.edit',['id'=> $client->id]) }}">
                                    <button class="btn btn-warning mb-1">Editar</button>
                                </a>
                                <a class="btn-link" onclick="deletar(this.id,'clients')" id="{{ $client->id }}">
                                    <button class="btn btn-danger mb-1">Deletar</button>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>

@endsection

