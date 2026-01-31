<?php

namespace App\Http\Livewire\Admin\ProductReport;

use DB;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class StockInserted extends Component
{
    use AlertMessage;
    use WithPagination;
	public $perPage, $orderList=[],$product, $searchName, $storeUser;
    protected $listeners = ['loadMore'];
    protected $paginationTheme = 'bootstrap';
	public function mount()
	{
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

    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }

	public function loadMore()
    {
        $this->perPage= $this->perPage+env('PER_PAGE', 50);
    }

    public function viewOrder($id)
    {
        if($this->storeUser == 1){
            $this->orderList = ProductOrder::with('customer')->where('product_id', $id)->get();
            $this->product = Product::find($id);
        }
        else{
            $this->orderList = ProductOrder2::with('customer')->where('product_id', $id)->get();
            $this->product = Product2::find($id);
        }
    	
    	$this->dispatchBrowserEvent('show-stock-inserted');
    }
    public function render()
    {
        if($this->storeUser == 1)
        	$productQuery = Product::with('gallery', 'productQuantities','productOrders','returnProductsQuantity')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty');
        else
            $productQuery = Product2::with('gallery', 'productQuantities','productOrders','returnProducts')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProducts', 'qty');

    	if ($this->searchName)
        {
           $productQuery = $productQuery->where('name', 'like', '%' . $this->searchName . '%')->orWhere('product_code', 'like', '%' . $this->searchName . '%');
        }
        return view('livewire.admin.product-report.stock-inserted', [
            'products' => $productQuery->orderBy('id', 'desc')->paginate($this->perPage)
        ]);
    }
}
