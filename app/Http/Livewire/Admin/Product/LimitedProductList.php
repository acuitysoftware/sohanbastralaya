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

class LimitedProductList extends Component
{

	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[];
    public $searchName, $storeUser, $perPage;
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers', 'loadMore'];

	public function mount()
    {
        $this->setting = Setting::first();
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
    	$productQuery = Product::withSum('productQuantities','quantity')->whereColumn('quantity', '<=' ,'default_quantity');
        else
            $productQuery = Product2::withSum('productQuantities','quantity')->whereColumn('quantity', '<=' ,'default_quantity');
    	if ($this->searchName)
    	{
           $productQuery = $productQuery->where('name', 'like', '%' . $this->searchName . '%')->orWhere('product_code', 'like', '%' . $this->searchName . '%');
    	}
        
    	return view('livewire.admin.product.limited-product-list', [
            'products' => $productQuery
                ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage)
        ]);
    }
   
}
