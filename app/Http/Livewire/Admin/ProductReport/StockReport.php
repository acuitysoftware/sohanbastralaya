<?php

namespace App\Http\Livewire\Admin\ProductReport;

use DB;
use Auth;
use Session;
use App\Models\Setting;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\ProductQuantity;
use App\Models\ProductQuantity2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class StockReport extends Component
{
    use WithPagination;
	public $perPage, $orderList=[], $storeUser, $setting, $dateForm, $dateTo;
    protected $listeners = ['loadMore'];
    protected $paginationTheme = 'bootstrap';
	public function mount()
	{
        $this->setting = Setting::first();
		$this->perPage =env('PER_PAGE', 50); 
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

    public function resetSearch()
    {
       $this->dateForm = null;
       $this->dateTo = null;
    }
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }

	public function loadMore()
    {
        $this->perPage= $this->perPage+env('PER_PAGE', 50);
    }

    public function viewOrders($value)
    {
        if($this->storeUser == 1)
        	$this->orderList = ProductQuantity::withCount('product')->having('product_count', '>',0)->with('product','product.gallery')->whereDate('date', $value)->get();
        else
            $this->orderList = ProductQuantity2::withCount('product')->with('product','product.gallery')->having('product_count', '>',0)->whereDate('date', $value)->get();

    	$this->dispatchBrowserEvent('show-stock-report'); 
    }

    public function render()
    {
        if($this->storeUser == 1)
        {
            $orderQuery = ProductQuantity::join('st_product', 'st_product.id', '=', 'st_product_quantity.product_id')
            ->select('st_product_quantity.date',DB::raw('sum(st_product_quantity.quantity*st_product.purchase_price) AS total_purchase_price'), DB::raw('sum(st_product_quantity.quantity*st_product.selling_price) AS total_selling_price'));
        }
        else
        {
            $orderQuery = ProductQuantity2::join('st_product', 'st_product.id', '=', 'st_product_quantity.product_id')
            ->select('st_product_quantity.date',DB::raw('sum(st_product_quantity.quantity*st_product.purchase_price) AS total_purchase_price'), DB::raw('sum(st_product_quantity.quantity*st_product.selling_price) AS total_selling_price'));
        }
           //dd($orderQuery->take(12)->get()); 

           if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $orderQuery = $orderQuery->where(DB::raw("DATE(st_product_quantity.date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $orderQuery = $orderQuery->where(DB::raw("DATE(st_product_quantity.date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }

        return view('livewire.admin.product-report.stock-report', [
            'orders' => $orderQuery->groupBy('st_product_quantity.date')
            ->orderBy('st_product_quantity.date', 'desc')->paginate($this->perPage)
        ]);
    }
}
