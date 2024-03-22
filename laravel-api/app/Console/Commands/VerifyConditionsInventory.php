<?php

namespace App\Console\Commands;

use App\Events\InventoryLoteCreated;
use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use App\Services\InventoryService;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Console\Command;
class VerifyConditionsInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-conditions-inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify products conditions in all inventories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $todayNow = Carbon::now();
        $expirationLimit = $todayNow->addMonths(4);

        Inventory::whereDate('expiration_date', '<=', $expirationLimit)->update(['condition_id' => 4]);
        Inventory::whereDate('expiration_date', '<=', $today)->update(['condition_id' => 3]);

        $productService = new ProductService;
        $productsAffected = $productService->generateArrayAllProductsAffected();
        
        InventoryLoteCreated::dispatch($productsAffected);
        
    }
}
