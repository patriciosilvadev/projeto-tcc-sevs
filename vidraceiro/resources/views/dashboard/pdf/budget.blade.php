<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orçamento - Vidraceiro</title>

    <style>
        p, h3, span {
            font-weight: 700;
            font-family: 'Raleway', sans-serif;
        }

        .line {
            border-bottom: 2px solid #e5e5e5;
            height: 2px;
        }

        h3 {
            background-color: #DFEBFF;
            padding: .4rem;
        }

        .borda-assinatura {
            width: 45.7%;
            height: 20px;
            display: inline-block;
            text-align: center;
            border-top: 2px solid rgba(0, 0, 0, 0.8);
            padding-top: .5rem;
        }

        .ml-auto {
            margin-left: 7.7%;
        }

        .mt {
            margin-top: 6rem;
        }

        .flex {
            width: 100%;
            margin-top: 40px;
        }

        .tabela-produto {
            width: 100px;
            height: auto;
            border-right: 0.4px solid #1b1e21;
        }

        .tabela-produto img {
            width: 100%;
            height: 100%;
        }

        table {
            width: 100%;
            font-family: 'Raleway', sans-serif;
            font-size: .9rem;
            border-spacing: 0;
            padding: 0;
            margin: 0 0 20px;
        }

        tr, td {
            border-spacing: 0;
            padding: .6rem;
            border: 1px solid #1b1e21;
        }

        .semborda {
            border-spacing: 0;
            padding: .6rem;
            border: none;
        }

        .indice {
            width: 10%;
            height: auto;
            text-align: center;
            border-right: 1px solid #1b1e21;
        }

        .texto {
            margin: 0 auto;
            text-align: left;
            vertical-align: center;
        }

        .total {
            display: block;
            position: relative;
            width: 100%;
            height: 35px;
        }

        .total p {
            margin: 0;
            padding: 0;
        }

        #texto-left {
            float: left;
        }

        #texto-right {
            float: right;
        }
    </style>
</head>
<body>

@if(!empty($company)) 
@php
    $location = $company->location()->first();
    $contact = $company->contact()->first();
    $telefone = $contact->telefone;
    if($telefone !== null){
    // primeiro substr pega apenas o DDD e coloca dentro do (), segundo subtr pega os números do 3º até faltar 4, insere o hifem, e o ultimo pega apenas o 4 ultimos digitos
    $telefone="(".substr($telefone,0,2).") ".substr($telefone,2,-4)." - ".substr($telefone,-4);
    }
@endphp
<p>{{$company->nome}}</p>
<p>{{$location->endereco .' - '. $location->bairro}}</p>
<p>{{$location->cidade .' - '. $location->uf}}</p>
<p>E-mail: {{$contact->email}}</p>
<p>Telefone: {{$telefone}}</p>
<div class="line"></div>
@endif

@php 
    $location = $budget->location()->first(); 
    $contact = $budget->contact()->first();
    $telefone = $contact->telefone;
    if($telefone !== null){
    // primeiro substr pega apenas o DDD e coloca dentro do (), segundo subtr pega os números do 3º até faltar 4, insere o hifem, e o ultimo pega apenas o 4 ultimos digitos
    $telefone="(".substr($telefone,0,2).") ".substr($telefone,2,-4)." - ".substr($telefone,-4);
    }
@endphp
<h3>Dados do orçamento</h3>
<p>Nome: {{$budget->nome}}</p>
<p>Endereço: {{$location->endereco .' - '. $location->bairro}}</p>
<p>Cep: {{$location->cep}}</p>
<p>Complemento: {{$location->complemento}}</p>
<p>Telefone: {{$telefone}}</p>
<h3>Produtos</h3>
<div class="total">
    <p id="texto-left">Valor total do orçamento: </p>
    <p id="texto-right">R$ {{$budget->total}}</p>
</div>

<div>
<table>
    @forelse($budget->products as $product)

        
            <tr>
                <td class="tabela-produto">
                    <img src="{{ public_path().$product->mproduct->imagem}}">
                </td>
                <td class="indice">{{$loop->index + 1}}</td>

                <td class="texto"><b>Nome: {{$product->mproduct->nome .' - '}}</b> {{$product->mproduct->descricao}}
                    <br>
                    <b>Linha:</b> {{$product->mproduct->category->nome}}
                    <br>
                    <b>Vidros utilizados:</b>
                    @foreach($product->glasses as $glass)
                        {{$glass->nome . ' | '}}
                    @endforeach
                    <br>
                    <b>Aluminios utilizados:</b>
                    @foreach($product->aluminums as $aluminum)
                        {{$aluminum->perfil . ' '. $aluminum->descricao . ' | '}}
                    @endforeach
                    <br>
                    <b>Componentes utilizados:</b>
                    @foreach($product->components as $component)
                        {{$component->nome . ' | '}}
                    @endforeach
                    <br>
                    <b>Largura:</b> {{$product->largura . ' '}}
                    <b>Altura:</b> {{$product->altura}}
                    <b>Quantidade:</b> {{$product->qtd}}
                    <br>
                    <b>Localização:</b> {{$product->localizacao}}
                </td>
            </tr>

            <tr><td class="semborda"></td></tr>
        
    @empty
        <tr><p style="margin-bottom: 100px;">Nenhum Produto Cadastrado.</p></tr>
        <tr><td class="semborda"></td></tr>
    @endforelse

</table>
    <div class="flex">
        <div class="borda-assinatura">
            <span>Local e Data</span>
        </div>
        <div class="borda-assinatura ml-auto">
            <span>Assinatura</span>
        </div>
    </div>
</div>


</body>
</html>