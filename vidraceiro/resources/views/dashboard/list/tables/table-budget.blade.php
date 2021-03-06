<table class="table table-hover">
    <thead>
    <tr>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Id</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Nome</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Data</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Total</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Status</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Usuário</th>
        <th class="noborder" scope="col" style="padding: 12px 30px 12px 16px;">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($budgets as $budget)
        <tr>
            <th scope="row">{{ $budget->id }}</th>
            <td>{{$budget->nome}}</td>
            <td>{{date_format(date_create($budget->data), 'd/m/Y')}}</td>
            <td style="color: #28a745;">R${{empty($budget->total) ? 0 : $budget->total}}</td>
            <td><span class="badge {{$budget->status === 'AGUARDANDO'?'badge-secondary'
                                        :($budget->status === 'APROVADO'?'badge-success':'badge-primary')}}">{{$budget->status}}</span>
            </td>
            @php $user = $budget->user()->first(); @endphp
            <td><span class="badge {{ !empty($user)? 'badge-primary' : 'badge-dark' }}">{{ !empty($user)? $user->name : 'Excluído' }}</span></td>
            <td>
                
                @if(Request::is('restore'))

                    <a class="btn-link" href="{{ route('restore.restore',['tipo'=>'orcamentos','id'=> $budget->id]) }}">
                        <button class="btn btn-light mb-1 card-shadow-1dp" title="Restaurar"><i class="fas fa-undo-alt"></i></button>
                    </a>
                @else
                    <a class="btn-link" href="{{ route('budgets.show',['id'=> $budget->id]) }}">
                        <button class="btn btn-light mb-1 card-shadow-1dp" title="Ver"><i class="fas fa-eye"></i></button>
                    </a>

                    @if($budget->status === 'AGUARDANDO')
                        <a class="btn-link" href="{{ route('budgets.edit',['id'=> $budget->id]) }}">
                            <button class="btn btn-warning mb-1 card-shadow-1dp pl-2 pr-2" title="Editar"><i
                                        class="fas fa-edit pl-1"></i></button>
                        </a>

                        <a class="btn-link" onclick="deletar(event,this.id,'budgets/budget')" id="{{ $budget->id }}">
                            <button class="btn btn-danger mb-1 card-shadow-1dp" title="Deletar"><i
                                        class="fas fa-trash-alt"></i></button>
                        </a>
                    @endif
                    <a class="btn-link" href="{{ route('budgets.create',['id'=> $budget->id]) }}" id="{{ $budget->id }}">
                        <button class="btn btn-success mb-1 card-shadow-1dp" title="Copiar e adicionar um novo orçamento"><i class="fas fa-copy"></i></button>
                    </a>

                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p class="m-4 text-center"
   style="font-weight: 600;"> {{ ($budgets->count() == 0) ? 'Nenhum orçamento encontrado': ''}}</p>
{{ $budgets->links('layouts.pagination') }}
