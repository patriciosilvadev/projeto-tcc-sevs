<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Uf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Location;
use App\Contact;

class ProviderController extends Controller
{

    protected $states;
    protected $provider = null;

    public function __construct(Provider $provider)
    {
        $this->middleware('auth');

        $this->provider = $provider;

        $this->states = Uf::getUfs();
    }

    public function index(Request $request)
    {
        if(!Auth::user()->can('fornecedor_listar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $providers = $this->provider->getWithSearchAndPagination($request->get('search'),$request->get('paginate'));

        if ($request->ajax()){
            return view('dashboard.list.tables.table-provider', compact('providers'));
        }else{
            return view('dashboard.list.provider', compact('providers'))->with('title', 'Fornecedores');
        }

    }

    public function create()
    {
        if(!Auth::user()->can('fornecedor_adicionar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $states = $this->states;
        return view('dashboard.create.provider', compact('states'))->with('title','Criar fornecedor');
    }

    public function store(Request $request)
    {
        if(!Auth::user()->can('fornecedor_adicionar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $validado = $this->rules_provider($request->all(),'');
        if ($validado->fails())
            return redirect()->back()->withErrors($validado);

        $location = new Location();
        $location = $location->createLocation($request->all());
        $contact = new Contact();
        $contact = $contact->createContact($request->all());

        $provider = $this->provider->createProvider(array_merge($request->all(),['endereco_id'=>$location->id,'contato_id'=>$contact->id]));
        if($provider)
            return redirect()->back()->with('success', 'Fornecedor criado com sucesso');
    }

    public function show($id)
    {
        if(!Auth::user()->can('fornecedor_listar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $validado = $this->rules_provider_exists(['id'=>$id]);

        if ($validado->fails())
            return redirect(route('providers.index'))->withErrors($validado);


        $provider = $this->provider->findProviderById($id);

        if($provider)
            return view('dashboard.show.provider', compact('provider'))->with('title', 'Informações do fornecedor');
    }

    public function edit($id)
    {
        if(!Auth::user()->can('fornecedor_atualizar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $validado = $this->rules_provider_exists(['id'=>$id]);

        if ($validado->fails())
            return redirect(route('providers.index'))->withErrors($validado);


        $provider = $this->provider->findProviderById($id);

        $states = $this->states;
        return view('dashboard.create.provider',compact('provider','states'))->with('title', 'Atualizar fornecedor');
    }


    public function update(Request $request, $id)
    {
        if(!Auth::user()->can('fornecedor_atualizar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }

        $validado = $this->rules_provider_exists(['id'=>$id]);

        if ($validado->fails())
            return redirect(route('providers.index'))->withErrors($validado);


        $validado = $this->rules_provider($request->all(),$id);

        if ($validado->fails())
            return redirect()->back()->withErrors($validado);


        $provider = $this->provider->findProviderById($id);
        $location = $provider->location()->first();
        $location->updateLocation($request->all());
        $contact = $provider->contact()->first();
        $contact->updateContact($request->all());
        $provider = $provider->updateProvider($request->all());

        if ($provider)
            return redirect()->back()->with('success', 'Fornecedor atualizado com sucesso');
    }

    public function destroy($id)
    {
        if(!Auth::user()->can('fornecedor_deletar', Provider::class)){
            return redirect('/home')->with('error', 'Você não tem permissão para acessar essa página');
        }
        $provider = $this->provider->findProviderById($id);

        if ($provider){
            $location = $provider->location()->first();
            $contact = $provider->contact()->first();
            $provider->deleteProvider();
            $location->deleteLocation();
            $contact->deleteContact();
            return redirect()->back()->with('success', 'Fornecedor deletado com sucesso');
        }

        return redirect()->back()->with('error', 'Erro ao deletar fornecedor');

    }

    public function rules_provider(array $data, $ignoreId)
    {
        $validator = Validator::make($data, [
            'nome' => 'required|string|max:255',
            'situacao' => 'required|string|max:255',
            'telefone' => 'nullable|string|min:10|max:255',
            'celular' => 'nullable|string|min:10|max:255',
            'cnpj' => 'nullable|cnpj|unique:providers,cnpj,'.$ignoreId,
            'cep' => 'required|string|min:8|max:8',
            'bairro' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cidade' => 'nullable|string|max:255',
            'uf' => 'nullable|string|max:255'
        ]);

        return $validator;
    }

    public function rules_provider_exists(array $data)
    {
        $validator = Validator::make($data,
            [
                'id' => 'exists:providers,id'
            ], [
                'exists' => 'Não existe este fornecedor!',
            ]
        );
        return $validator;
    }

}
