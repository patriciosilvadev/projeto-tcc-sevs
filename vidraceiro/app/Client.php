<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'documento',
        'status',
        'endereco_id',
        'contato_id'
    ];

    public function location(){
        return $this->hasOne(Location::class,'id','endereco_id');
    }

    public function contact(){
        return $this->hasOne(Contact::class,'id','endereco_id');
    }

    public function budgets(){
        return $this->hasMany(Budget::class,'cliente_id');
    }

    public function getWithSearchAndPagination($search, $paginate, $restore = false, $havePermission = true){

        $paginate = $paginate ?? 10;

        $queryBuilder = self::where(function ($q) use ($search,$havePermission){
            $q->where('nome', 'like', '%' . $search . '%')
                ->orWhere('documento', 'like', '%' . $search . '%');
                if($havePermission)
                    $q->orWhere('status', 'like', '%' . $search . '%');
        });
        if($restore){
            $queryBuilder = $queryBuilder->onlyTrashed();
        }

        return $queryBuilder->paginate($paginate);
    }

    public function createClient(array $input){

        return self::create($input);

    }

    public function updateClient(array $input){

        return self::update($input);

    }

    public function deleteClient(){

        return self::delete();

    }

    public function findClientById($id){

        return self::find($id);

    }

    public function restoreClientById($id){

        $client = self::onlyTrashed()->find($id);

        return $client? $client->restore(): false;
    }

    public static function getAllClients(){

        return self::all();

    }

    public function getBudgetsWithSale(){

        return $this->budgets()->with('sale')->get();

    }

    public function updateStatus(){
        $emDia = false;
        $budgets = $this->getBudgetsWithSale();

        foreach ($budgets as $budget){
            if(!empty($budget->sale)){
                $emDia = !($budget->sale->havePendingInstallment());
                if(!$emDia)
                    break;
            }
        }

        if($emDia){
            $this->updateClient(['status' => 'EM DIA']);
        }
    }

    public function haveBudgetApproved(){
        return !empty($this->budgets()->where('status','APROVADO')->first());
    }

    public function updateClientBudgets(array $input){
        $budgetUpdated = false;
        foreach (self::budgets()->with('location','contact')->get() as $budget) {
            $budget->contact->updateContact(['telefone'=>$input['telefone']]);
            $budgetUpdated = $budget->location->updateLocation($input);
        }
        return $budgetUpdated;
    }

    public static function filterClients($request, $dontHavePermission){

        $status = $request->status;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $clients = new Client();

        if($data_inicial !== null || $data_final !== null){

            $clients = self::where(function ($q) use ($data_inicial,$data_final){
                if($data_final !== null)
                    $q->whereDate('created_at','<=',$data_final);

                if($data_inicial !== null)
                    $q->whereDate('created_at','>=',$data_inicial);
            });
        }

        if($status === 'TODOS' || $dontHavePermission){
            $clients = $clients->get();
        }else{
            $clients = $clients->where('status',$status)->get();
        }

        return $clients;

    }
}
