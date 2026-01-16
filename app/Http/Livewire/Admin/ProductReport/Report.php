<?php

namespace App\Http\Livewire\Admin\ProductReport;

use DB;
use Hash;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use App\Models\ReturnProduct;
use App\Models\ReturnProduct2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class Report extends Component
{
    use AlertMessage;
	public  $totalOrder,$total_purchase, $total_profit,$total_selling_price=0, $total_purchase_price=0, $product_selling_price=0, $product_purchase_price=0, $inseted_product_selling_price=0, $inseted_product_purchase_price=0,$products, $storeUser;
	public function mount()
	{
		if(Auth::user()->type=='A')
        {
            $this->storeUser = 1;
            $store = Session::get('store');
            if($store)
            {
                $this->storeUser = $store;
            }
        }
        else{

            $this->storeUser = Auth::user()->store;
        }
        $this->product_selling_price =0;
        $this->product_purchase_price =0;
        $this->inseted_product_selling_price =0;
        $this->inseted_product_purchase_price =0;
        
	}
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }
    public function render()
    {
        $this->product_selling_price =0;
        $this->product_purchase_price =0;
        $this->inseted_product_selling_price =0;
        $this->inseted_product_purchase_price =0;
        if($this->storeUser == 1)
        {
            $totalOrder= ProductOrder::select(DB::raw("sum(qty*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(qty*selling_price) as 'total_selling_price'"))->get();
            $this->total_selling_price = $totalOrder[0]->total_selling_price;
            $this->total_purchase_price = $totalOrder[0]->total_purchasing_price;
            $this->total_profit = ($this->total_selling_price-$this->total_purchase_price);
            
           

            $productQuery = Product::with('productQuantities')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->get();

            if(count($productQuery))
            {
                foreach ($productQuery as $key => $value) {
                    $avl_qty =0;
                    $avl_qty = $value->product_quantities_sum_quantity-($value->return_products_quantity_sum_qty+$value->product_orders_sum_qty+$value->product_reductions_sum_qty);

                    /* $this->inseted_product_purchase_price+=$value->purchase_price*$value->product_quantities_sum_quantity; 
                    $this->inseted_product_selling_price+=$value->selling_price*$value->product_quantities_sum_quantity;  */

                    $this->product_selling_price+= ($value->selling_price*$avl_qty);
                    $this->product_purchase_price+= ($value->purchase_price*$avl_qty);
                }
            }
            $this->inseted_product_selling_price = (float)$this->total_selling_price+(float)$this->product_selling_price; 
            $this->inseted_product_purchase_price = (float)$this->total_purchase_price+(float)$this->product_purchase_price;
        }
        else
        {
            $totalOrder= ProductOrder2::select(DB::raw("sum(qty*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(qty*selling_price) as 'total_selling_price'"))->get();
            $this->total_selling_price = $totalOrder[0]->total_selling_price;
            $this->total_purchase_price = $totalOrder[0]->total_purchasing_price;
            $this->total_profit = ($this->total_selling_price-$this->total_purchase_price);

            $productQuery = Product2::with('productQuantities')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->get();

            if(count($productQuery))
            {
                foreach ($productQuery as $key => $value) {
                    $avl_qty =0;
                    $avl_qty = $value->product_quantities_sum_quantity-($value->return_products_quantity_sum_qty+$value->product_orders_sum_qty+$value->product_reductions_sum_qty);
                    /* $this->inseted_product_purchase_price+=$value->purchase_price*$value->product_quantities_sum_quantity; 
                    $this->inseted_product_selling_price+=$value->selling_price*$value->product_quantities_sum_quantity; */ 
                    $this->product_selling_price+= ($value->selling_price*$avl_qty);
                    $this->product_purchase_price+= ($value->purchase_price*$avl_qty);
                }
            }
            $this->inseted_product_selling_price = (float)$this->total_selling_price+(float)$this->product_selling_price; 
            $this->inseted_product_purchase_price = (float)$this->total_purchase_price+(float)$this->product_purchase_price; 
        }

        
        
        return view('livewire.admin.product-report.report');
    }
}
