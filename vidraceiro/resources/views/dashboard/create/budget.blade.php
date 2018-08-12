@extends('layouts.app')
@section('content')

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card-material custom-card">

            <!-- Inicio tab de Produto-->
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @for($i = 0; $i < count($titulotabs); $i++)
                        @if($i == 0)
                            <a class="nav-item nav-link {{ (empty(session('budgetcriado')) || !empty($budgetedit)) ? 'active' : 'disabled' }} noborder-left"
                               id="nav-{{$titulotabs[$i]}}-tab"
                               data-toggle="tab"
                               href="#nav-{{$titulotabs[$i]}}" role="tab" aria-controls="nav-{{$titulotabs[$i]}}"
                               aria-selected="true">{{$titulotabs[$i]}}</a>
                        @elseif($i == 1)
                            <a class="nav-item nav-link {{ (empty(session('budgetcriado')))? !empty($budgetedit)? '':'disabled' : 'active' }}"
                               id="nav-{{$titulotabs[$i]}}-tab" data-toggle="tab"
                               href="#nav-{{$titulotabs[$i]}}" role="tab" aria-controls="nav-{{$titulotabs[$i]}}"
                               aria-selected="false">{{$titulotabs[$i]}}</a>
                        @else
                            <a class="nav-item nav-link {{ (empty(session('budgetcriado'))&& empty($budgetedit)) ? 'disabled' : '' }}"
                               id="nav-{{$titulotabs[$i]}}-tab" data-toggle="tab"
                               href="#nav-{{$titulotabs[$i]}}" role="tab" aria-controls="nav-{{$titulotabs[$i]}}"
                               aria-selected="false">{{$titulotabs[$i]}}</a>
                        @endif
                    @endfor

                    {{--<!-- INICIO ABA EXTRA AO EDITAR ORÇAMENTO -->--}}

                    {{--<a class="nav-item nav-link" id="nav-editar-tab" data-toggle="tab"--}}
                    {{--href="#nav-editar" role="tab" aria-controls="nav-editar"--}}
                    {{--aria-selected="false">Editar</a>--}}

                    {{--<!-- FIM ABA EXTRA AO EDITAR ORÇAMENTO -->--}}

                    {{--<a class="nav-item nav-link" id="nav-adicionar-tab" data-toggle="tab"--}}
                    {{--href="#nav-adicionar" role="tab" aria-controls="nav-adicionar"--}}
                    {{--aria-selected="false">Adicionar</a>--}}

                    {{--<a class="nav-item nav-link" id="nav-material-tab" data-toggle="tab"--}}
                    {{--href="#nav-material" role="tab" aria-controls="nav-material"--}}
                    {{--aria-selected="false">Material</a>--}}

                    {{--<a class="nav-item nav-link" id="nav-total-tab" data-toggle="tab"--}}
                    {{--href="#nav-total" role="tab" aria-controls="nav-total"--}}
                    {{--aria-selected="false">Total</a>--}}

                    <div class="topo-tab">
                        <button id="bt-budget-visible" class="btn btn-primary btn-custom" type="submit">
                            Salvar
                        </button>
                    </div>
                </div>
            </nav>
            <!-- Fim tab de Produto-->

            <!--Inicio Conteudo de cada tab -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ (empty(session('budgetcriado')) || !empty($budgetedit)) ? 'show active' : '' }} "
                     id="nav-{{$titulotabs[0]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[0]}}-tab">

                    <form id="form-product" class="formulario" method="POST" role="form"
                          action="{{ !empty($budgetedit) ?  route('budgets.update',['id'=>$budgetedit->id,'tag' => '1']) :  route('budgets.store',['tag' => '1'])}}">
                        @if(!empty($budgetedit))
                            <input type="hidden" name="_method" value="PATCH">
                        @endif
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">

                            <div class="col-12">
                                @if(session('success'))
                                    <div class="alerta p-0">
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
                            </div>

                            <div class="form-group col-md-4">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome"
                                       value="{{$budgetedit->nome or old('nome')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="data">Data</label>
                                <input type="date" class="form-control" id="data" name="data" placeholder="00/00/0000"
                                       value="{{$budgetedit->data or old('data')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="telefone">Telefone</label>
                                <input type="tel" class="form-control" id="telefone" name="telefone"
                                       placeholder="(00)0000-0000"
                                       value="{{$budgetedit->telefone or old('telefone')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="rua">Rua</label>
                                <input type="text" class="form-control" id="rua" name="rua"
                                       placeholder="av. de algum lugar"
                                       value="{{$budgetedit->rua or old('rua')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="cep">Cep</label>
                                <input type="number" class="form-control" id="cep" name="cep" placeholder="00000-000"
                                       value="{{$budgetedit->cep or old('cep')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="numero_endereco">N°</label>
                                <input type="number" class="form-control" id="numero_endereco" name="numero_endereco"
                                       placeholder="100"
                                       value="{{$budgetedit->numero_endereco or old('numero_endereco')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro"
                                       placeholder="bairro" value="{{$budgetedit->bairro or old('bairro')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="select-UF">UF</label>
                                <select id="select-UF" name="uf" class="custom-select">
                                    @foreach ($states as $uf => $estado)
                                        <option value="{{$uf}}"
                                        @if(!empty($budgetedit)){{ $budgetedit->uf == $uf ? 'selected' :''}} @endif>{{$estado}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade"
                                       placeholder="cidade" value="{{$budgetedit->cidade or old('cidade')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="complemento">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento"
                                       placeholder="complemento"
                                       value="{{$budgetedit->complemento or old('complemento')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="margem_lucro">Margem de lucro</label>
                                <input type="number" class="form-control" id="margem_lucro" name="margem_lucro"
                                       placeholder="100" value="{{$budgetedit->margem_lucro or old('margem_lucro')}}">
                            </div>


                        </div>

                        <button id="bt-orcamento-budget-invisible" class="d-none" type="submit"></button>

                    </form>

                </div>

                <div class="tab-pane fade {{ !empty(session('budgetcriado')) ? 'show active' : '' }}"
                     id="nav-{{$titulotabs[1]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[1]}}-tab">

                    <form class="formulario" method="POST" role="form"
                          action="{{ !empty($budgetedit) ?  route('budgets.update',['id'=>$budgetedit->id,'tag' => '2']) :  route('budgets.store',['tag' => '2'])}}">
                        @if(!empty($budgetedit))
                            <input type="hidden" name="_method" value="PATCH">
                        @endif
                        @csrf
                        <div class="form-row">

                            <div class="col-12">
                                @if(session('success'))
                                    <div class="alerta p-0">
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
                            </div>

                            <div class="form-group col-md">
                                <label for="select-tipo-produto">Selecione um tipo</label>
                                <select id="select-tipo-produto" class="custom-select" required>
                                    <option value="" selected>Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-row align-items-center">
                            <div class="form-group col-">
                                <img id="image-mproduto" src="{{ '/img/semimagem.png' }}" class="img-fluid"
                                     alt="Responsive image" style="height: 110px!important;">
                            </div>

                            <div class="form-group col-md">
                                <label for="select-mproduto">Selecione o produto</label>
                                <select id="select-mproduto" name="m_produto_id" class="custom-select" required>
                                    <option id="option-vazia" value="" selected>Selecione um produto</option>
                                    @foreach($mproducts as $mproduct)
                                        <option data-descricao="{{$mproduct->descricao}}"
                                                data-image="{{$mproduct->imagem}}"
                                                data-categoria="{{$mproduct->categoria_produto_id}}"
                                                class="mprodutos-options" value="{{$mproduct->id}}"
                                                style="display: none;">{{$mproduct->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="descricao-mprod">Descrição</label>
                                <input type="text" class="form-control" id="descricao-mprod" placeholder="Descrição">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="altura">Altura</label>
                                <input type="number" step="0.001" class="form-control" id="altura" name="altura"
                                       placeholder="0,000"
                                       value="{{old('altura')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="largura">Largura</label>
                                <input type="number" step="0.001" class="form-control" id="largura" name="largura"
                                       placeholder="0,000" value="{{old('largura')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="qtd">Quantidade</label>
                                <input type="number" class="form-control" id="qtd" name="qtd"
                                       placeholder="quantidade" value="{{old('qtd')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="localizacao">Localização</label>
                                <input type="text" class="form-control" id="localizacao" name="localizacao"
                                       placeholder="Localização" value="{{old('localizacao')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="valor_mao_obra">Valor da mão de obra</label>
                                <input type="number" class="form-control" id="valor_mao_obra" name="valor_mao_obra"
                                       placeholder="" value="{{old('valor_mao_obra')}}">
                            </div>
                            @if(empty($budgetedit))
                                <input type="hidden" name="budgetid"
                                       value="{{!empty(session('budgetcriado'))?Session::get('budgetcriado')->id : '' }}">
                            @endif
                        </div>

                        <button id="bt-add-budget-invisible" class="d-none" type="submit"></button>

                    </form>

                </div>

                <!-- INICIO CONTEUDO ABA EXTRA AO EDITAR ORÇAMENTO -->
                <div class="tab-pane fade"
                     id="nav-{{$titulotabs[2]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[2]}}-tab">

                    <form class="formulario" method="POST" role="form"
                          action="{{ !empty($budgetedit) ?  route('budgets.update',['id'=>$budgetedit->id,'tag' => '3']) :  route('budgets.store',['tag' => '3'])}}">
                        @if(!empty($budgetedit))
                            <input type="hidden" name="_method" value="PATCH">
                        @endif
                        @csrf
                        <div class="form-row align-items-center">

                            <div class="col-12">
                                @if(session('success'))
                                    <div class="alerta p-0">
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
                            </div>

                            <div class="form-group col-">
                                <img id="image-produto-editar" src="{{ '/img/semimagem.png' }}" class="img-fluid"
                                     alt="Responsive image" style="height: 110px!important;">
                            </div>

                            <div class="form-group col-md">
                                <label for="select-produto">Selecione o produto</label>
                                <select id="select-produto-edit" name="produtoid" class="custom-select" required>
                                    <option value="" selected>Selecione um produto</option>
                                    @if(!empty(session('products')))
                                        @foreach(Session::get('products') as $product)

                                            <option data-descricao="{{$product->mproduct->descricao}}"
                                                    data-image="{{$product->mproduct->imagem}}"
                                                    data-altura="{{$product->altura}}"
                                                    data-largura="{{$product->largura}}"
                                                    data-qtd="{{$product->qtd}}"
                                                    data-localizacao="{{$product->localizacao}}"
                                                    data-valor_mao_obra="{{$product->valor_mao_obra}}"
                                                    value="{{$product->id}}"
                                            >{{$product->mproduct->nome}}</option>

                                        @endforeach
                                    @endif
                                    @if(!empty($products))
                                        @foreach($products as $product)

                                            <option data-descricao="{{$product->mproduct->descricao}}"
                                                    data-image="{{$product->mproduct->imagem}}"
                                                    data-altura="{{$product->altura}}"
                                                    data-largura="{{$product->largura}}"
                                                    data-qtd="{{$product->qtd}}"
                                                    data-localizacao="{{$product->localizacao}}"
                                                    data-valor_mao_obra="{{$product->valor_mao_obra}}"
                                                    value="{{$product->id}}"
                                            >{{$product->mproduct->nome}}</option>

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="descricao-edit">Descrição</label>
                                <input type="text" class="form-control" id="descricao-edit" placeholder="Descrição">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="altura-edit">Altura</label>
                                <input type="number" step="0.001" class="form-control" id="altura-edit" name="altura"
                                       placeholder="0,000"
                                       value="{{old('altura')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="largura-edit">Largura</label>
                                <input type="number" step="0.001" class="form-control" id="largura-edit" name="largura"
                                       placeholder="0,000" value="{{old('largura')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="qtd-edit">Quantidade</label>
                                <input type="number" class="form-control" id="qtd-edit" name="qtd"
                                       placeholder="quantidade" value="{{old('qtd')}}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="localizacao-edit">Localização</label>
                                <input type="text" class="form-control" id="localizacao-edit" name="localizacao"
                                       placeholder="Localização" value="{{old('localizacao')}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="valor_mao_obra-edit">Valor da mão de obra</label>
                                <input type="number" class="form-control" id="valor_mao_obra-edit" name="valor_mao_obra"
                                       placeholder="" value="{{old('valor_mao_obra')}}">
                            </div>
                            @if(empty($budgetedit))
                                <input type="hidden" name="budgetid"
                                       value="{{!empty(session('budgetcriado'))?Session::get('budgetcriado')->id : '' }}">
                            @endif
                        </div>

                        <button id="bt-edit-budget-invisible" class="d-none" type="submit"></button>

                    </form>

                </div>
                <!-- FIM CONTEUDO ABA EXTRA AO EDITAR ORÇAMENTO -->

                <div class="tab-pane fade" id="nav-{{$titulotabs[3]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[3]}}-tab">

                    <form class="formulario" method="POST" role="form"
                          action="{{ !empty($budgetedit) ?  route('budgets.update',['id'=>$budgetedit->id,'tag' => '4']) :  route('budgets.store',['tag' => '4'])}}">
                        @if(!empty($budgetedit))
                            <input type="hidden" name="_method" value="PATCH">
                        @endif
                        @csrf
                        <div class="form-row align-items-center">

                            <div class="col-12">
                                @if(session('success'))
                                    <div class="alerta p-0">
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
                            </div>

                            <div class="form-group col-">
                                <img id="image-produto-material" src="{{ '/img/semimagem.png' }}" class="img-fluid"
                                     alt="Responsive image" style="height: 110px!important;">
                            </div>

                            <div class="form-group col-md">
                                <label for="select-produto">Selecione o produto</label>
                                <select id="select-produto-material" class="custom-select" required>
                                    <option value="" selected>Selecione um produto</option>
                                    @if(!empty(session('products')))
                                        @foreach(Session::get('products') as $product)

                                            <option data-image="{{$product->mproduct->imagem}}"
                                                    value="{{$product->id}}">{{$product->mproduct->nome}}</option>

                                        @endforeach
                                    @endif

                                    @if(!empty($products))
                                        @foreach($products as $product)

                                            <option data-image="{{$product->mproduct->imagem}}"
                                                    value="{{$product->id}}">{{$product->mproduct->nome}}</option>

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                    @include('layouts.listarmaterial')

                            @if(empty($budgetedit))
                                <input type="hidden" name="budgetid"
                                       value="{{!empty(session('budgetcriado'))?Session::get('budgetcriado')->id : '' }}">
                            @endif
                        <button id="bt-material-budget-invisible" class="d-none" type="submit"></button>

                    </form>

                </div>

                <div class="tab-pane fade" id="nav-{{$titulotabs[4]}}" role="tabpanel"
                     aria-labelledby="nav-{{$titulotabs[4]}}-tab">

                    <form class="formulario" method="POST" role="form"
                          action="{{ !empty($budget) ?  route('budgets.update',['id'=>$budget->id,'tag' => '5']) :  route('budgets.store',['tag' => '5'])}}">
                        @if(!empty($budget))
                            <input type="hidden" name="_method" value="PATCH">
                        @endif
                        @csrf
                        <div class="form-row">

                            <div class="card-material custom-card custom-card-total col-md p-3">
                                <div class="topo px-0 py-0 h-auto">
                                    <h4 class="card-title cor-texto">Total</h4>
                                </div>

                                @if(!empty(session('products')))
                                    @foreach(Session::get('products') as $product)

                                        <label class="card-text">{{$product->mproduct->nome}}</label>

                                    @endforeach
                                @endif
                                @if(!empty($products))
                                    @foreach($products as $product)

                                        <label class="card-text">{{$product->mproduct->nome}}</label>

                                    @endforeach
                                @endif

                            </div>

                        </div>
                        <div class="form-row">

                            <div class="card-material custom-card custom-card-total col-md p-3">
                                <div class="topo px-0 py-0 h-auto">
                                    <h4 class="card-title cor-texto">Materiais</h4>
                                </div>
                                <label class="card-text">Vidro 1</label>
                                <label class="card-text">Vidro 1</label>
                                <label class="card-text">Vidro 1</label>
                                <label class="card-text">Vidro 1</label>
                                <label class="card-text">Vidro 1</label>

                            </div>

                        </div>

                        <div class="form-row">

                            <div class="card-material custom-card custom-card-total col-md p-3">
                                <div class="topo px-0 py-0 h-auto">
                                    <h4 class="card-title cor-texto">Componentes</h4>
                                </div>
                                <label class="card-text">Roldana</label>
                                <label class="card-text">Roldana</label>
                                <label class="card-text">Roldana</label>
                            </div>

                        </div>

                        <button id="bt-total-budget-invisible" class="d-none" type="submit"></button>

                    </form>

                </div>

            </div>
            <!--Fim Conteudo de cada tab -->

        </div>
    </div>
@endsection