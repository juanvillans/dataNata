<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\EntryCollection;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class InventoryService extends ApiService
{   

    protected $snakeCaseMap = [

        'productId' => 'product_id',
        'loteNumber' => 'lote_number',
        'categoryId' => 'category_id',
        'typePresentationId' => 'type_presentation_id',
        'typeAdministrationId' => 'type_administration_id',
        'medicamentId' => 'medicament_id',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',

    ];

    private $wantSeeOtherEntity;
    private $codeToSee;



    public function __construct()
    {
        parent::__construct(new InventoryGeneral);
    }

    public function getData($paginateArray, $queryArray, $userEntityCode)
    {   
        $this->wantSeeOtherEntity = false;
        $this->codeToSee = $userEntityCode;

        $inventories = InventoryGeneral::select([
            'inventory_generals.*',
            'hierarchy_entities.name as entity_name',
            'products.name as product_name',
            'categories.name as category_name',
            'type_administrations.name as type_administration_name',
            'type_presentations.name as type_presentation_name',
            'medicaments.name as medicament_name',
        ])
        ->join('hierarchy_entities','inventory_generals.entity_code','=','hierarchy_entities.code')
        ->join('products','inventory_generals.product_id','=','products.id')
        ->join('categories','products.category_id','=','categories.id')
        ->join('type_presentations','products.type_presentation_id','=','type_presentations.id')
        ->join('type_administrations','products.type_administration_id','=','type_administrations.id')
        ->join('medicaments','products.medicament_id','=','medicaments.id');



        foreach ($queryArray as $table => $array )
        {       

            if($table == 'search')
                $table = 'inventory_generals';
            
            $inventories = $inventories->where(function ($query) use ($table, $array) {
            
            foreach ($array as $params)
            {   
                
                if($params[0] == 'entity_code')
                {
                    $this->wantSeeOtherEntity = true;
                    $this->codeToSee = $params[2];
                }
                
                elseif ($params[0] == 'stock_good') 
                {
                    if (isset($params[3])) {
                        $query->orWhere($table . '.' . $params[0], '<>', $params[2]);
                    } else {
                        $query->where($table . '.' . $params[0], '<>', $params[2]);
                    }
                } 

                elseif ($params[0] == 'stock_expired') 
                {
                    if (isset($params[3])) {
                        $query->orWhere($table . '.' . $params[0], '<>', $params[2]);
                    } else {
                        $query->where($table . '.' . $params[0], '<>', $params[2]);
                    }
                } 

                elseif ($params[0] == 'stock_bad') 
                {
                    if (isset($params[3])) {
                        $query->orWhere($table . '.' . $params[0], '<>', $params[2]);
                    } else {
                        $query->where($table . '.' . $params[0], '<>', $params[2]);
                    }
                } 

                elseif ($params[0] == 'stock_per_expire') {
                    if (isset($params[3])) {
                        $query->orWhere($table . '.' . $params[0], '<>', $params[2]);
                    } else {
                        $query->where($table . '.' . $params[0], '<>', $params[2]);
                    }
                } 
                
                else 
                {
                    if (isset($params[3])) {
                        $query->orWhere($table . '.' . $params[0], $params[1], $params[2]);
                    } else {
                        $query->where($table . '.' . $params[0], $params[1], $params[2]);
                    }
                }
            }

            });

        }
    
        if($userEntityCode == '1' && $this->wantSeeOtherEntity == true)
        {   
            if($this->codeToSee !== '*')
            {   
                $inventories = $inventories->where('inventory_generals.entity_code','=',$this->codeToSee);
            }
        }
        else
            $inventories = $inventories->where('inventory_generals.entity_code','=',$userEntityCode);


        $inventories = $inventories->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $inventories;

    }


}
