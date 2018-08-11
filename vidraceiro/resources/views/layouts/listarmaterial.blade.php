<div class="form-row">
    <div class="form-group col-md-8">
        <label for="select-material">Materiais</label>
        <select id="select-material" class="custom-select">
            <option value="0">Vidros</option>
            <option value="1">Aluminios</option>
            <option value="2">Componentes</option>
        </select>
    </div>
</div>


<div class="form-row mt-3 align-items-end">

    <div class="form-group col-md-4">
        <label for="select-vidro" id="label_categoria">Vidros</label>
        <select id="select-vidro" name="vidro_id" class="custom-select" required>
            <option value="" selected>Selecione um vidro</option>
            @foreach($glasses as $glass)
                <option data-preco="{{$glass->preco}}" value="{{$glass->id}}">{{$glass->nome}}</option>
            @endforeach
        </select>
        <select id="select-aluminio" name="aluminio_id" class="custom-select"
                style="display: none;" required>
            <option value="" selected>Selecione um aluminio</option>
            @foreach($aluminums as $aluminum)
                <option data-medida="{{$aluminum->medida}}"
                        data-peso="{{$aluminum->peso}}"
                        data-preco="{{$aluminum->preco}}"
                        value="{{$aluminum->id}}">{{$aluminum->perfil}}</option>
            @endforeach
        </select>
        <select id="select-componente" name="componente_id" class="custom-select"
                style="display: none;" required>
            <option value="" selected>Selecione um componente</option>
            @foreach($components as $component)
                <option data-qtd="{{$component->qtd}}"
                        data-preco="{{$component->preco}}"
                        value="{{$component->id}}">{{$component->nome}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">

        <button id="bt-add-material-mproduct" class="btn btn-primary btn-block btn-custom"
                type="button">
            Adicionar
        </button>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 p-0">
        <div class="topo pl-2">
            <h4 class="titulo">Vidros</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <!--INICIO HEAD DO VIDRO-->
                <tr id="topo-vidro">
                    <th class="noborder" scope="col">Id</th>
                    <th class="noborder" scope="col">Nome</th>
                    <th class="noborder" scope="col">Preço m²</th>
                    <th class="noborder" scope="col">Ação</th>
                </tr>
                <!--FIM HEAD DO VIDRO-->

                <!--INICIO HEAD DO ALUMINIO-->
                <tr id="topo-aluminio" style="display: none;">
                    <th class="noborder" scope="col">Id</th>
                    <th class="noborder" scope="col">Perfil</th>
                    <th class="noborder" scope="col">Medida</th>
                    <th class="noborder" scope="col">Peso</th>
                    <th class="noborder" scope="col">Preço</th>
                    <th class="noborder" scope="col">Ação</th>
                </tr>
                <!--FIM HEAD DO ALUMINIO-->

                <!--INICIO HEAD DO COMPONENTE-->
                <tr id="topo-componente" style="display: none;">
                    <th class="noborder" scope="col">Id</th>
                    <th class="noborder" scope="col">Nome</th>
                    <th class="noborder" scope="col">Preço</th>
                    <th class="noborder" scope="col">Qtd</th>
                    <th class="noborder" scope="col">Ação</th>
                </tr>
                <!--FIM HEAD DO COMPONENTE-->

                </thead>

                <!--INICIO BODY DO VIDRO-->
                <tbody id="tabela-vidro">
                @if(!empty($mproductedit))
                    @foreach($glassesProduct as $glassP)
                        <tr id="linha-vidro-{{$glassP->id}}">
                            <th scope="row">{{$glassP->id}}</th>
                            <td>{{$glassP->nome}}</td>
                            <td>R${{$glassP->preco}}</td>
                            <td>
                                <a class="btn-link">
                                    <button class="btn btn-danger mb-1">Delete</button>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>

                <!--FIM BODY DO VIDRO-->

                <!--INICIO BODY DO ALUMINIO-->
                <tbody id="tabela-aluminio" style="display: none;">
                @if(!empty($mproductedit))
                    @foreach($aluminumsProduct as $aluminumP)
                        <tr>
                            <th scope="row">{{$aluminumP->id}}</th>
                            <td>{{$aluminumP->perfil}}</td>
                            <td>{{$aluminumP->medida}}</td>
                            <td>{{$aluminumP->peso}}</td>
                            <td>{{$aluminumP->preco}}</td>
                            <td>
                                <a class="btn-link">
                                    <button class="btn btn-danger mb-1">Delete</button>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <!--FIM BODY DO ALUMINIO-->

                <!--INICIO BODY DO COMPONENTE-->
                <tbody id="tabela-componente" style="display: none;">
                @if(!empty($mproductedit))
                    @foreach($componentsProduct as $componentP)
                        <tr>
                            <th scope="row">{{$componentP->id}}</th>
                            <td>{{$componentP->nome}}</td>
                            <td>{{$componentP->preco}}</td>
                            <td>{{$componentP->qtd}}</td>
                            <td>
                                <a class="btn-link">
                                    <button class="btn btn-danger mb-1">Delete</button>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
                <!--FIM BODY DO COMPONENTE-->

            </table>


        </div>
    </div>

</div>

<!-- Ids -->
<div class="form-row">
    <div class="form-group col-12">
        <div id="ids">
            @if(!empty($mproductedit))
                @foreach($aluminumsProduct as $aluminumP)
                    <input type="number" class="id-material linha-aluminio-{{$aluminumP->id}}"
                           name="aluminio_id[]"
                           value="{{$aluminumP->id}}" style="display: block;"/>
                @endforeach
                @foreach($glassesProduct as $glassP)
                    <input type="number" class="id-material linha-vidro-{{$glassP->id}}"
                           name="vidro_id[]"
                           value="{{$glassP->id}}" style="display: block;"/>
                @endforeach
                @foreach($componentsProduct as $componentP)
                    <input type="number"
                           class="id-material linha-componente-{{$componentP->id}}"
                           name="componente_id[]"
                           value="{{$componentP->id}}" style="display: block;"/>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- FIM ids -->