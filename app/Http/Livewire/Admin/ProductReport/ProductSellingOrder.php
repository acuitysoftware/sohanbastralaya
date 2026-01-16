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
	public $perPage, $orderList=[],$product, $dateForm, $dateTo,$storeUser;
    protected $listeners = ['loadMore'];
	public function mount()
	{
		$this->perPage =200; 
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
        $this->perPage= $this->perPage+200;
    }

    public function resetSearch()
    {
       $this->dateForm = null;
       $this->dateTo = null;
    }

    public function render()
    {
        if($this->storeUser == 1)
        	$productQuery = Product::withCount('productOrders')->having('product_orders_count', '>', 0)->withSum('productOrders', 'qty');
        else
            $productQuery = Product2::withCount('productOrders')->having('product_orders_count', '>', 0)->withSum('productOrders', 'qty');
        
    	if ($this->dateForm && $this->dateTo)
    	{
    		$date['form_date'] = $this->dateForm;
    		$date['to_date'] = $this->dateTo;
           $productQuery = $productQuery->whereBetween('post_date',[$date['form_date'],$date['to_date']]);
    	}
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $productQuery = $productQuery->where(DB::raw("DATE(post_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $productQuery = $productQuery->where(DB::raw("DATE(post_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        return view('livewire.admin.product-report.product-selling-order', [
            'products' => $productQuery->orderBy('product_orders_sum_qty', 'desc')->paginate($this->perPage)
        ]);
    }
}
