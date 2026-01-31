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
            $totalOrder= ProductOrder::select(DB::raw("sum(qty*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(subtotal) as 'total_subtotal'"),DB::raw('sum(qty*discount) AS total_discount_amount'))->first();
            $total_discount = $totalOrder->total_discount_amount;
            $this->total_selling_price = ((float)$totalOrder->total_subtotal-(float)$total_discount);
            $this->total_purchase_price = $totalOrder->total_purchasing_price;
            $this->total_profit = (((float)$this->total_selling_price-(float)$this->total_purchase_price));

            $productData= Product::select(DB::raw("sum(quantity*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(quantity*selling_price) as 'total_sell_price'"))->first();
            $this->product_selling_price = $productData->total_sell_price;
            $this->product_purchase_price = $productData->total_purchasing_price;
            /* dump(  $this->product_selling_price);
            dd( $productData); */
            
            $insertedData = Product::join('st_product_quantity', 'st_product_quantity.product_id', '=', 'st_product.id')->select(DB::raw("sum(st_product_quantity.quantity*st_product.selling_price) as inseted_selling_price"), DB::raw("sum(st_product_quantity.quantity*st_product.purchase_price) as inseted_purchase_price"))->get();
             $this->inseted_product_selling_price = $insertedData[0]->inseted_selling_price; 
            $this->inseted_product_purchase_price = $insertedData[0]->inseted_purchase_price;

           
        }
        else
        {
           $totalOrder= ProductOrder2::select(DB::raw("sum(qty*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(subtotal) as 'total_subtotal'"),DB::raw('sum(qty*discount) AS total_discount_amount'))->first();
            $total_discount = $totalOrder->total_discount_amount;
            $this->total_selling_price = ((float)$totalOrder->total_subtotal-(float)$total_discount);
            $this->total_purchase_price = $totalOrder->total_purchasing_price;
            $this->total_profit = (((float)$this->total_selling_price-(float)$this->total_purchase_price));

            $productData= Product2::select(DB::raw("sum(quantity*purchase_price) as 'total_purchasing_price'"), DB::raw("sum(quantity*selling_price) as 'total_sell_price'"))->get();
            $this->product_selling_price = $productData[0]->total_sell_price;
            $this->product_purchase_price = $productData[0]->total_purchasing_price;
            
            $insertedData = Product2::join('st_product_quantity', 'st_product_quantity.product_id', '=', 'st_product.id')->select(DB::raw("sum(st_product_quantity.quantity*st_product.selling_price) as inseted_selling_price"), DB::raw("sum(st_product_quantity.quantity*st_product.purchase_price) as inseted_purchase_price"))->get();
             $this->inseted_product_selling_price = $insertedData[0]->inseted_selling_price; 
            $this->inseted_product_purchase_price = $insertedData[0]->inseted_purchase_price; 
        }

        
        
        return view('livewire.admin.product-report.report');
    }
}
