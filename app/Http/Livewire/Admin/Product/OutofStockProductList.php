<?php

namespace App\Http\Livewire\Admin\Product;

use Auth;
use Session;
use Hash;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;
class OutofStockProductList extends Component
{
    use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[];
    public $searchName, $storeUser, $perPage;
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers', 'loadMore'];
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
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }
    public function loadMore()
    {
        $this->perPage= $this->perPage+env('PER_PAGE', 50);
    }
	public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }
    public function resetSearch()
    {
       
    }
    
    public function render()
    {
        if ($this->storeUser == 1)
    	    $productQuery = Product::withSum('productQuantities','quantity')->where('quantity', 0);
        else
            $productQuery = Product2::withSum('productQuantities','quantity')->where('quantity', 0);
    	if ($this->searchName)
    	{
           $productQuery = $productQuery->where('name', 'like', '%' . $this->searchName . '%')->orWhere('product_code', 'like', '%' . $this->searchName . '%');
    	}
        
    	return view('livewire.admin.product.outof-stock-product-list', [
            'products' => $productQuery
                ->orderBy('id', 'desc')->paginate($this->perPage)
        ]);
    }
   
}
