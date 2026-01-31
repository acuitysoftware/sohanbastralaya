<?php

namespace App\Http\Livewire\Admin\Product;

use Auth;
use Hash;
use Validator;
use Session;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Gallery2;
use App\Models\Product;
use App\Models\Product2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class UploadProductImage extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[],$perPage;
    public $searchName, $storeUser;
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers','loadMore'];
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
            $productQuery = Product::has('gallery', '==', '0');
        else
            $productQuery = Product2::has('gallery', '==', '0');
        if ($this->searchName)
        {
            $name =$this->searchName;
           $productQuery = $productQuery->where(function($q) use($name){
            $q ->where('name', 'like', '%' . $name . '%')->orWhere('product_code', 'like', '%' . $name . '%')->orWhere('bar_code', 'like', '%' . $name . '%');
           });
          
        }
           
        return view('livewire.admin.product.upload-product-image', [
            'products' => $productQuery
                ->orderBy('id', 'desc')
                ->paginate($this->perPage)
        ]);
    }

    public function editProductImage($id)
    {
        if ($this->storeUser == 1)
            $product = Product::with('gallery')->find($id);
        else
            $product = Product2::with('gallery')->find($id);
        $this->state['product_id'] = $product->id;
        $this->state['image'] = null;
        $this->state['gallery_image'] = isset($product->gallery)?$product->gallery->gallery_image:null;
        $this->dispatchBrowserEvent('show-product-image-form');
    }

    public function save()
    {
        if ($this->storeUser == 1)
            $updateProduct = Product::find($this->state['product_id']);
        else
            $updateProduct = Product::find($this->state['product_id']);
        Validator::make($this->state,[
            'image' => 'required',
        ])->validate();
        if (isset($this->state['image']) && !is_string($this->state['image'])) 
        {
            $product_img = $this->state['image'];
            $filename = time() . '-' . rand(1000, 9999) . '.' . $product_img->getClientOriginalExtension();
            $product_img->storeAs("public/product_image", $filename);
            if(isset($updateProduct->gallery))
            {
                @unlink(storage_path('app/public/product_image/' . $updateProduct->gallery->gallery_image));
            }
            if ($this->storeUser == 1){
                Gallery::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            }else{
                Gallery2::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            }
        }
        $this->dispatchBrowserEvent('hide-product-image-form');
        $this->showModal('success', 'Success', 'Image Update successfully');
    }

}
