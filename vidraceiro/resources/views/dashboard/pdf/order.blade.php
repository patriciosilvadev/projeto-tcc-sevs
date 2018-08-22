<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Título Opcional</title>

    <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
    {{--<link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">--}}
    <style>
        p {
            font-weight: 700;
            font-family: 'Raleway', sans-serif;
        }

        .line {
            border-bottom: 2px solid #1b1e21;
        }
    </style>
</head>
<body>

<p>{{$company->nome}}</p>
<p>{{$company->endereco .' - '. $company->bairro}}</p>
<p>{{$company->cidade .' - '. $company->uf}}</p>
<p>E-mail: {{$company->email}}</p>
<p>Telefone: {{$company->telefone}}</p>
<div class="line"></div>

<h1>{{$order->nome}}</h1>

@foreach($order->budgets as $budget)
    <h4>{{$budget->nome}}</h4>
    <ul>

        @forelse($budget->products as $product)
            <li style="color: #0acf97;">{{ $product->mproduct->nome }}</li>
        @empty
            <li>Nenhum Produto Cadastrado.</li>
        @endforelse
    </ul>

@endforeach


</body>
</html>