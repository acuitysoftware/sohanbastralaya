<?php

namespace App\Http\Livewire\Admin\ProductReport;

use DB;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class ProductSellingOrder extends Component
{
    use WithPagination;
	public $perPage, $orderList=[],$product, $dateForm, $dateTo,$storeUser,$searchName;
    protected $listeners = ['loadMore'];
    protected $paginationTheme = 'bootstrap';
	public function mount()
	{
		$this->perPage = env('PER_PAGE', 50); 
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
	}
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }

	public function loadMore()
    {
        $this->perPage= $this->perPage+env('PER_PAGE', 50);
    }

    public function resetSearch()
    {
       $this->dateForm = null;
       $this->dateTo = null;
    }

    public function render()
    {
        if($this->storeUser == 1){

            $productQuery = DB::table('st_product')->join('st_product_order', 'st_product_order.product_id', '=', 'st_product.id')->join('st_product_order_details', 'st_product_order_details.order_id', '=', 'st_product_order.order_id')->select('st_product.id','st_product.name', 'st_product.product_code', 'st_product_order.selling_price', DB::raw("sum(st_product_order.qty) as product_orders_sum_qty"))->groupBy('st_product.name');
        	//$productQuery = Product::withCount('productOrders')->having('product_orders_count', '>', 0)->withSum('productOrders', 'qty');
            //dd($productQuery->limit(50)->get());
        }
        else
            /* $productQuery = Product2::withCount('productOrders')->having('product_orders_count', '>', 0)->withSum('productOrders', 'qty'); */
            $productQuery = Product2::join('st_product_order', 'st_product_order.product_id', '=', 'st_product.id')->join('st_product_order_details', 'st_product_order_details.order_id', '=', 'st_product_order.order_id')->select('st_product.id','st_product.name', 'st_product.product_code', 'st_product_order.selling_price', DB::raw("sum(st_product_order.qty) as product_orders_sum_qty"))->groupBy('st_product.name');

        if ($this->searchName)
        {
            $nam = $this->searchName;
           $productQuery = $productQuery->where(function($q) use($nam){
            $q->where('st_product.name', 'like', '%' . $nam . '%')->orWhere('st_product.product_code', 'like', '%' . $nam . '%');
           });
           
        }
        
    	if ($this->dateForm && $this->dateTo)
    	{
    		$date['form_date'] = $this->dateForm;
    		$date['to_date'] = $this->dateTo;
           $productQuery = $productQuery->whereBetween('st_product_order_details.order_date',[$date['form_date'],$date['to_date']]);
    	}
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $productQuery = $productQuery->where(DB::raw("DATE(st_product_order_details.order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $productQuery = $productQuery->where(DB::raw("DATE(st_product_order_details.order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
      // dd($productQuery->take(50)->get());
        /* return view('livewire.admin.product-report.product-selling-order', [
            'products' => $productQuery->orderBy('product_orders_sum_qty', 'desc')->paginate($this->perPage)
        ]); */
        return view('livewire.admin.product-report.product-selling-order', [
            'products' => $productQuery->orderBy('product_orders_sum_qty', 'desc')->paginate($this->perPage)
        ]);
    }
}
