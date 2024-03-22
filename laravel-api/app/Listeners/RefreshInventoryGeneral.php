<?php

namespace App\Listeners;

use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RefreshInventoryGeneral
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {   

        $arrayProduct = $event->arrayProductWithEntityCode;
        
        foreach ($arrayProduct as $entityCode => $arrayProductIds)
        {
            $inventoriesLote = Inventory::where('entity_code',$entityCode)->whereIn('product_id',$arrayProductIds)->with('product')->get();


            $dataToInsert = [];


            foreach ($inventoriesLote as $inventory)
            {   

                $productMinimumStock = $inventory->product->minimum_stock;
                $alert = 0;

                if(!array_key_exists($inventory->product_id, $dataToInsert))
                {
                    $stock = 0;
                    $stockGood = 0;
                    $stockPerExpire = 0;
                    $stockExpired = 0;
                    $stockBad = 0;
                    $entries = 0;
                    $outputs = 0;

                    if($inventory->condition_id == 1)
                    {   
                        $stockGood += $inventory->stock;
                        $stock += $inventory->stock;
                    }

                    elseif($inventory->condition_id == 2)
                    {
                        $stockBad += $inventory->stock;
                        $stock += $inventory->stock;

                    }

                    elseif($inventory->condition_id == 3)
                    {
                        $stockExpired += $inventory->stock;
                        $stock += $inventory->stock;

                    }
                    elseif($inventory->condition_id == 4)
                    {
                        $stockPerExpire += $inventory->stock;
                        $stock += $inventory->stock;
                    }

                    if($productMinimumStock >= $stockGood)
                        $alert = 1;

                    $dataToInsert[$inventory->product_id] = ['stock' => $stock, 'stock_good' => $stockGood, 'stock_expired' => $stockExpired, 'stock_bad' => $stockBad, 'stock_per_expire' => $stockPerExpire, 'entries' => $inventory->entries, 'outputs' => $inventory->outputs, 'minimum_alert' => $alert];
                }
                else
                {
                    if($inventory->condition_id == 1)
                    {
                        $dataToInsert[$inventory->product_id]['stock'] += $inventory->stock;
                        $dataToInsert[$inventory->product_id]['stock_good'] += $inventory->stock;

                    }

                    elseif($inventory->condition_id == 2)
                    {
                        $dataToInsert[$inventory->product_id]['stock'] += $inventory->stock;
                        $dataToInsert[$inventory->product_id]['stock_bad'] += $inventory->stock;

                    }

                    elseif($inventory->condition_id == 3)
                    {
                        $dataToInsert[$inventory->product_id]['stock'] += $inventory->stock;
                        $dataToInsert[$inventory->product_id]['stock_expired'] += $inventory->stock;

                    }

                    elseif($inventory->condition_id == 4)
                    {
                        $dataToInsert[$inventory->product_id]['stock'] += $inventory->stock;
                        $dataToInsert[$inventory->product_id]['stock_per_expire'] += $inventory->stock;

                    }


                    if($productMinimumStock >= $dataToInsert[$inventory->product_id]['stock_good'])
                        $alert = 1;

                    $dataToInsert[$inventory->product_id]['entries']+= $inventory->entries;
                    $dataToInsert[$inventory->product_id]['outputs']+= $inventory->outputs;
                    $dataToInsert[$inventory->product_id]['minimum_alert'] = $alert;


                }
                
            }


            foreach ($dataToInsert as $productId => $values)
            {

                $search = Product::select('search')->where('id',$productId)->first();
                $new = InventoryGeneral::updateOrCreate([

                    'entity_code' => $entityCode,
                    'product_id' => $productId,

                ],
                [
                    'stock' => $values['stock'],
                    'stock_bad' => $values['stock_bad'],
                    'stock_good' => $values['stock_good'],
                    'stock_expired' => $values['stock_expired'],
                    'stock_per_expire' => $values['stock_per_expire'],
                    'entries' => $values['entries'],
                    'outputs' => $values['outputs'],
                    'minimum_alert' => $values['minimum_alert'],
                    'search' => $search->search,
                ]
             );


            }
            


            
        }



        

      

        
    }
}
