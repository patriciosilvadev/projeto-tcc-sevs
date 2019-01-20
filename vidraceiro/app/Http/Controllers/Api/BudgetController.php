<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Uf;
use Illuminate\Http\Request;
use App\Budget;
use App\Aluminum;
use App\Product;
use App\MProduct;
use App\Component;
use App\Glass;
use App\Category;
use App\Client;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Array_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    protected $states;
    protected $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
        $this->states = Uf::getUfs();
    }

    public function index(Request $request)
    {
        if (!Auth::user()->can('orcamento_listar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página'], 401);
        }

        $budgets = $this->budget->getWithSearchAndPagination($request->get('search'), false, false, false, true);

        return response()->json(['budgets' => $budgets]);

    }

    public function create()
    {
        if (!Auth::user()->can('orcamento_adicionar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página'], 401);
        }

        //$states = $this->states;
        $clients = Client::getAllClients();
        $mproducts = MProduct::getAllMProducts();
        $categories = Category::getAllCategories("produto");
        $categoriesmaterials = Category::getAllCategoriesMaterials();

        return response()->json(['clients' => $clients, 'mproducts' => $mproducts, 'categories' => $categories, 'categoriesmaterials' => $categoriesmaterials], 200);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('orcamento_adicionar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página'], 202);
        }

        $validado = $this->rules_budget($request->all());
        if ($validado->fails())
            return response()->json(['error' => $validado->messages()], 202);

        $margemlucro = $request->margem_lucro ?? 100;

        $budgetcriado = $this->budget->createBudget(array_merge($request->except('margem_lucro'), ['margem_lucro' => $margemlucro, 'status' => 'AGUARDANDO', 'total' => 0, 'usuario_id' => Auth::user()->id]));


        if ($budgetcriado)
            return response()->json(['success' => 'Orçamento criado com sucesso', 'id' => $budgetcriado->id], 200);


        return response()->json(['error' => 'Erro ao adicionar'], 202);
    }

    public function show($id)
    {
        if (!Auth::user()->can('orcamento_listar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página']);
        }

        $validado = $this->rules_budget_exists(['id' => $id]);

        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 401);
        }

        $budget = $this->budget->findBudgetById($id);
        return response()->json(['budget' => $budget]);
    }

    public function edit($id)
    {
        if (!Auth::user()->can('orcamento_atualizar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página']);
        }

        $validado = $this->rules_budget_exists(['id' => $id]);

        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 401);
        }

        $budgetedit = $this->budget->findBudgetById($id);

        if ($budgetedit->status !== 'AGUARDANDO') {
            return response()->json(['error' => 'Este orçamento não pode ser editado!']);
        }

//        $states = $this->states;
//        $aluminums = Aluminum::getAllAluminumsOrAllModels(1);
//        $glasses = Glass::getAllGlassesOrAllModels(1);
//        $components = Component::getAllComponentsOrAllModels(1);
//        $categories = Category::getAllCategoriesByType('produto');
//        $categories = Category::getAllCategories('produto');
        $mproducts = MProduct::getAllMProducts();
        $clients = Client::getAllClients();


        if ($budgetedit) {
            $products = $budgetedit->getBudgetProductsWithRelations();

            return response()->json([
//                'states' => $states,
//                'glasses' => $glasses,
//                'aluminums' => $aluminums,
//                'components' => $components,
//                'categories' => $categories,
                'mproducts' => $mproducts,
                'products' => $products,
                'budgetedit' => $budgetedit,
                'clients' => $clients]);
        }

        return response()->json(['error' => 'Erro ao buscar orçamento']);

    }


    public function update(Request $request, $tab, $id)
    {
        if (!Auth::user()->can('orcamento_atualizar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página'], 401);
        }

        $validado = $this->rules_budget_exists(['id' => $id]);

        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 202);
        }

        $budgetcriado = $this->budget->findBudgetById($id);

        if ($budgetcriado->status !== 'AGUARDANDO') {
            return response()->json(['error' => 'Este orçamento não pode ser editado!', 'res' => true], 202);
//            return response()->json(['error' => ['error' => ['Este orçamento não pode ser editado!']]], 202);

        }

        switch ($tab) {
            case '1': //tab orçamento
                $validado = $this->rules_budget($request->all());

                if ($validado->fails()) {
                    return response()->json(['error' => $validado->messages()], 202);
                }

                $margemlucro = $request->margem_lucro ?? 100;

                $budgetcriado->updateBudget(array_merge($request->except('margem_lucro'), ['margem_lucro' => $margemlucro]));
                if ($budgetcriado && $budgetcriado->updateBudgetTotal())
                    return response()->json(['success' => 'Orçamento atualizado com sucesso', 'id' => $id], 200);
                break;
            case '2': //tab adicionar
                $validado = $this->rules_budget_product($request->all(), ['m_produto_id' => 'required|integer']);

                if ($validado->fails()) {
                    return response()->json(['error' => $validado->messages()], 202);
                }

                $product = new Product();
                $product = $product->createProduct(array_merge($request->all(), ['budget_id' => $id]));

                $product->createMaterialsOfMProductToProduct();

                if ($product) {
                    //$budgetcriado = Budget::with('products.mproduct','products.glasses','products.aluminums','products.components')->find($id);
                    $budgetcriado->load('products.mproduct', 'products.glasses', 'products.aluminums', 'products.components');
                    if ($budgetcriado && $budgetcriado->updateBudgetTotal())
                        return response()->json(['success' => 'Produto adicionado ao orçamento com sucesso', 'budget' => $budgetcriado], 200);
                }
                break;
            case '3': //tab editar
                $validado = $this->rules_budget_product_exists(['produtoid' => $request->get('produtoid')]);

                if (!$validado->fails()) {
                    $validado = $this->rules_budget_product($request->all(), []);
                }

                if ($validado->fails()) {
                    return response()->json(['error' => $validado->messages()], 202);
                }

                $product = new Product();
                $product = $product->findProductById($request->produtoid);
                $product->updateProduct($request->all());
                $product->updateAluminunsWithProductMeasure();
                //$budgetcriado = $this->budget->findBudgetById($id);
                $budgetcriado->load('products.mproduct', 'products.glasses', 'products.aluminums', 'products.components');

                if ($product && $budgetcriado->updateBudgetTotal())
                    return response()->json(['success' => 'Produto atualizado com sucesso', 'budget' => $budgetcriado], 200);

                break;
            case '4': //tab material
//                return response()->json(['error' => $request->all(), 'res' => $id], 202);
                $budgetcriado->load('products.mproduct', 'products.glasses', 'products.aluminums', 'products.components');
                $products = $budgetcriado->products;
                foreach ($products as $product) {
                    $product->createMaterialsToProduct($request->all());
                }
                if ($products && $budgetcriado->updateBudgetTotal())
                    return response()->json(['success' => 'Materiais dos produtos atualizados com sucesso', 'id' => $id], 200);
                break;
            default:
        }
        return response()->json(['error' => 'Erro ao atualizar'], 202);
    }

    public function destroy($del, $id)
    {
        if (!Auth::user()->can('orcamento_deletar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página', 'res' => true], 401);
        }

        if ($del == 'budget') {
            $budget = $this->budget->findBudgetById($id);
            if ($budget->status !== 'AGUARDANDO') {
                return response()->json(['error' => 'Este orçamento não pode ser deletado!', 'res' => true], 202);
            }
            if ($budget) {

                $budget->deleteBudget();
                return response()->json(['success' => 'Orçamento deletado com sucesso', 'id' => $id], 200);
            } else {
                return response()->json(['error' => 'Erro ao deletar orçamento', 'res' => true], 202);
            }
        } else {

            $product = Product::findProductsWithRelations([$id]);
            $product = $product->shift();

            if ($product) {
                $budgetcriado = $product->budget;

                if ($budgetcriado->status !== 'AGUARDANDO') {
                    return response()->json(['error' => 'Este orçamento não pode ser deletado!', 'res' => true], 202);
                }

                $product->deleteProduct();

                $budgetcriado->load('products.mproduct', 'products.glasses', 'products.aluminums', 'products.components');
                if ($budgetcriado->updateBudgetTotal()) {
                    return response()->json(['success' => 'Produto deletado com sucesso', 'budget' => $budgetcriado], 200);
                }
            } else {
                return response()->json(['error' => 'Erro ao deletar produto', 'res' => true], 202);
            }

        }

    }


    public function editMaterial($type, $id)
    {
        if (!Auth::user()->can('orcamento_atualizar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página']);
        }

        switch ($type) {
            case 'glass':
                $glass = new Glass();
                $material = $glass->findGlassById($id);

                $tabela = 'glasses';
                break;
            case 'aluminum':
                $aluminum = new Aluminum();
                $material = $aluminum->findAluminumById($id);

                $tabela = 'aluminums';
                break;
            case 'component':
                $component = new Component();
                $material = $component->findComponentById($id);

                $tabela = 'components';
                break;
        }

        $validado = $this->rules_budget_material_exists(['id' => $id], $tabela);

        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 401);
        } else {
            if ($material->is_modelo === 1) {
                return response()->json(['error' => 'Este material não existe!'], 401);
            }
        }


        if ($material) {

            return response()->json([
                'material' => $material,
                'type' => $type]);
        }
        return response()->json(['error' => 'Erro ao editar material']);

    }

    public function updateMaterial(Request $request, $type, $id)
    {
        if (!Auth::user()->can('orcamento_atualizar', Budget::class)) {
            return response()->json(['error' => 'Você não tem permissão para acessar essa página']);
        }

        $validado = $this->rules_budget_materiais($request->all(), $type);
        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 401);
        }

        $tabela = '';
        switch ($type) {
            case 'glass':

                $glass = new Glass();
                $material = $glass->findGlassById($id);
                $nome = 'Vidro';
                $tabela = 'glasses';

                break;
            case 'aluminum':

                $aluminum = new Aluminum();
                $material = $aluminum->findAluminumById($id);
                $nome = 'Alumínio';
                $tabela = 'aluminums';

                break;
            case 'component':

                $component = new Component();
                $material = $component->findComponentById($id);
                $nome = 'Componente';
                $tabela = 'components';

                break;
        }


        $validado = $this->rules_budget_material_exists(['id' => $id], $tabela);

        if ($validado->fails()) {
            return response()->json(['error' => $validado->messages()], 401);
        } else {
            if ($material->is_modelo === 1) {
                return response()->json(['error' => 'Este material não existe!'], 401);
            }
        }

        $product = $material->product;
        $budget = $product->findProductById($product->id)->budget;

        if ($budget->status !== 'AGUARDANDO') {
            return response()->json(['error' => 'Este orçamento não pode ser deletado!']);
        }

        switch ($type) {
            case 'glass':

                $material->updateGlass($request->all());

                break;
            case 'aluminum':

                $material->updateAluminum($request->all());

                break;
            case 'component':

                $material->updateComponent($request->all());

                break;
        }

        if ($material) {

            if ($budget && $budget->updateBudgetTotal()) {
                return response()->json(['success' => "$nome atualizado com sucesso"]);
            }


        }
        return response()->json(['error' => 'Erro!']);

    }


    public function rules_budget(array $data)
    {
        $validator = Validator::make($data, [
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|min:10|max:255',
            'cep' => 'required|digits:8',
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'uf' => ['required',
                Rule::notIn('Selecionar')],
            'complemento' => 'nullable|string|max:255',
            'margem_lucro' => 'nullable|numeric|max:255'
        ]);

        return $validator;
    }

    public function rules_budget_product(array $data, array $mproductValidation)
    {
        $validator = Validator::make($data, array_merge(
            $mproductValidation,
            [
                'largura' => 'required|numeric|max:255',
                'altura' => 'required|numeric|max:255',
                'qtd' => 'required|integer',
                'localizacao' => 'nullable|string|max:255',
                'valor_mao_obra' => 'nullable|numeric'
            ]
        ));

        return $validator;
    }

    public function rules_budget_product_exists(array $data)
    {
        $validator = Validator::make($data, [
            'produtoid' => 'required|integer|exists:products,id'
        ], [
            'exists' => 'Este produto não existe!',
        ]);

        return $validator;
    }

    public function rules_budget_exists(array $data)
    {
        $validator = Validator::make($data,

            [
                'id' => 'exists:budgets,id'
            ], [
                'exists' => 'Este orçamento não existe!',
            ]

        );

        return $validator;
    }

    public function rules_budget_material_exists(array $data, $tabela)
    {

        $validator = Validator::make($data,

            [
                'id' => 'exists:' . $tabela . ',id'
            ], [
                'exists' => 'Este material não existe!',
            ]

        );

        return $validator;
    }

    public function rules_budget_materiais(array $data, $type)
    {
        switch ($type) {
            case 'glass':
                $validator = Validator::make($data, [
                    'preco' => 'nullable|numeric'
                ]);
                break;
            case 'component':
            case 'aluminum':
                $validator = Validator::make($data, [
                    'qtd' => 'required|integer',
                    'preco' => 'nullable|numeric'
                ]);
                break;

        }

        return $validator;
    }

}
