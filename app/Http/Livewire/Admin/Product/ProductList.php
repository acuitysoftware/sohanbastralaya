<?php

namespace App\Http\Livewire\Admin\Product;

use Str;
use Auth;
use Hash;
use Session;
use Validator;
use App\Models\Setting;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\CartItem;
use App\Models\CartItem2;
use App\Models\Gallery;
use App\Models\Gallery2;
use App\Models\EditProductStock;
use App\Models\EditProductStock2;
use App\Models\ProductQuantity;
use App\Models\ProductQuantity2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;
use DNS1D;
class ProductList extends Component
{

	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $viewProduct =[], $type='edit', $deleteIds=[];
    public $searchName, $name, $product_code, $quantity, $default_quantity, $purchase_price, $selling_price, $image, $storeUser,$productSearch =[], $gallery_image, $product_id, $cart_quantity=[], $discount=[], $cart_count,$perPage,$count_cart_item,$user_type, $store, $printProducts, $viewOrder, $setting, $formSubmit, $discount_product_id=[], $discount_amt, $discount_type,$is_discount, $edit_is_discount,$bar_code;
	protected $listeners = ['deleteConfirm', 'changeStatus', 'loadMore', 'viewProductData'];
    
	public function generateBarCode($id){
        if($this->storeUser == 1)
        {
            $productData = Product::find($id);
        }
        else{
            $productData = Product2::find($id);

        }
        if($productData->bar_code){

        }
        else{

            $bar_code = null;
            while (true) {
                $numSeed = "0123456789";
                $shuffled = str_shuffle($numSeed);
                $bar_code  =  substr($shuffled,1,20);
                $bar_code = $bar_code;
                $oldData = Product::where('bar_code', $bar_code)->count();
                if($oldData == 0)
                {
                    break;
                }
            }
            
            $productData->update(['bar_code' => $bar_code]);
        }
        $this->bar_code = $productData->bar_code;
        $this->dispatchBrowserEvent('view-barcode');
       // $bar_code = $productData->bar_code;
       /*  $fileName = $bar_code.'.png';
        \File::put(storage_path(). '/app/public/bar_code/' . $fileName, base64_decode(DNS1D::getBarcodePNG($url, 'QRCODE'))); */
    }
	public function mount()
    {   
        $this->perPage =200;
        $this->formSubmit = 0;
        $this->setting = Setting::first();
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
        if($this->storeUser == 1)
        {
            $count = Product::where('quantity', '>',0)->count();
            $this->count_cart_item = CartItem::where('user_id', Auth::user()->id)->count();
        }
        else{

            $count = Product2::where('quantity', '>',0)->count();
            $this->count_cart_item = CartItem2::where('user_id', Auth::user()->id)->count();
        }

        if($this->storeUser == 1)
        {
            $data = Product::where('quantity', '>',0)->orderBy('id', 'desc')->get();
            foreach ($data as $key => $value) {
                # code...
            $this->discount_product_id[$key] = $value->id;
            }
        }
        else{

            $data = Product2::where('quantity', '>',0)->orderBy('id', 'desc')->get();
            foreach ($data as $key => $value) {
                # code...
            $this->discount_product_id[$key] = $value->id;
            }
        }


        for($i=0; $i<$count;$i++)
        {
            $this->cart_quantity[$i] =1;
        }


    }
    public function loadMore()
    {
        $this->perPage= $this->perPage+200;
    }
    
	

    public function updatedIsDiscount($value)
    {
        if($value){
            $this->is_discount = true;
        }
        else{

            $this->is_discount = false;
            $this->discount_amt = 0;
            $this->discount_type = null;
            $this->state['is_discount'] = false;
            $this->state['discount_amt'] = 0;
            $this->state['discount_type'] = null;

        }
    }
    public function updatedDiscount()
    {
        
        if(count($this->discount))
        {
           
            foreach($this->discount as $key=>$value)
            {
                $this->total_amount=0.00; 
                if(isset($value) && $value !="" && is_numeric($value))
                {
                    if($this->storeUser == 1)
                        $dis_product = Product::find($this->discount_product_id[$key]);
                    else
                        $dis_product = Product2::find($this->discount_product_id[$key]);

                    if($dis_product->is_discount)
                    {
                        if($dis_product->discount_type == 'Flat')
                        {
                            if((float)$value > $dis_product->discount_amt)
                            {
                                
                                $this->discount[$key] = null;
                                $this->showModal('error', 'Error', 'Maximum discount upto '.$dis_product->discount_amt);
                                
                            }
                        }
                        else{
                            $max_dis = (($dis_product->selling_price*$dis_product->discount_amt)/100);
                            if((float)$value > $max_dis)
                            {
                                
                                $this->discount[$key] = null;
                                $this->showModal('error', 'Error', 'Maximum discount upto '.$max_dis);
                                
                            }
                        }
                    }
                    else{


                        $current_discount = (((float)$dis_product->selling_price*(float)$this->setting->discount_percentage)/100);
                        /*dump((float)$value);
                        dd($current_discount);*/
                        if((float)$value > $current_discount)
                        {
                            
                            $this->discount[$key] = null;
                            $this->showModal('error', 'Error', 'Maximum discount upto '.$this->setting->discount_percentage. '%');
                            
                        }
                    }
                    
                }
                
            }
        }
        
    }
    public function updatedName($data)
    {
        //dd($data);
       if($data){
            if($this->storeUser == 1)
            {
                $this->productSearch = Product::select('id', 'product_code', 'name')->where('name', 'like', '%' . $data . '%')->orWhere('product_code', 'like', '%' . $data . '%')->get();
            }
            else{
                $this->productSearch = Product2::select('id', 'product_code', 'name')->where('name', 'like', '%' . $data . '%')->orWhere('product_code', 'like', '%' . $data . '%')->get();

            }
        }
        else{

            $this->productSearch = [];
        }
    }
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }
    public function getProductDetails($id)
    {   
        if($this->storeUser == 1)
        {
            $search_product = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);
        }
        else{
            $search_product = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);

        }
       $this->productSearch = [];
       $this->product_id = $search_product->id;
       $this->name = $search_product->name;
       $this->bar_code = $search_product->bar_code;
       $this->product_code = $search_product->product_code;
       $this->quantity = $search_product->product_quantities_sum_quantity-($search_product->return_products_quantity_sum_qty+$search_product->product_orders_sum_qty+$search_product->productReductions->sum('qty'));
       $this->default_quantity = $search_product->default_quantity;
       $this->selling_price = $search_product->selling_price;
       $this->purchase_price = $search_product->purchase_price;
       $this->is_discount = $search_product->is_discount;
       $this->discount_amt = $search_product->discount_amt;
       $this->discount_type = $search_product->discount_type;
       if(isset($search_product->gallery))
       {
            $this->gallery_image = $search_product->gallery->gallery_image;
       }
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
        {

            $productQuery = Product::with('gallery')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->withSum('productReductions', 'qty')->where('quantity', '>',0);
            //$printProductsQuery = Product::with('gallery')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty');
        }else{

            $productQuery = Product2::with('gallery')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->withSum('productReductions', 'qty')->where('quantity', '>',0);
            //$printProductsQuery = Product2::with('gallery')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty');
        }
        if ($this->searchName)
        {
           $productQuery = $productQuery->where('name', 'like', '%' . $this->searchName . '%')->orWhere('product_code', 'like', '%' . $this->searchName . '%');
           //$printProductsQuery = $printProductsQuery->where('name', 'like', '%' . $this->searchName . '%')->orWhere('product_code', 'like', '%' . $this->searchName . '%');
        }
        //$this->printProducts = $printProductsQuery->get();
        //dd($this->printProducts);
        return view('livewire.admin.product.product-list', [
            'products' => $productQuery
                ->orderBy('id', 'desc')
                ->paginate($this->perPage)
        ]);
    }

    public function updatedEditIsDiscount($value)
    {
        if($value){
            $this->edit_is_discount = true;
        }
        else{

            $this->edit_is_discount = false;
            $this->state['discount_amt'] = 0;
            $this->state['discount_type'] = null;
        }
    }
    public function save()
    {
        /*dd($this);*/
        if($this->product_id)
        {
            $this->validate([
                'name' => 'required',
                'discount_type' => 'required_if:is_discount,1',
                'discount_amt' => 'required_if:is_discount,1|max:10',
                /*'product_code' => 'required|regex:/^[A-Z]{6,6}$/|unique:st_product,product_code,'.$updateProduct->id,*/
                'quantity' => 'required',
               /* 'discount_type' => 'required_if:is_discount,==,For 1',*/
                'default_quantity' => 'required',
                'purchase_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
            ]);

            if($this->is_discount)
            {


            /*if($this->discount_type == 'Percentage')
            {
                $max_dis =(float)$this->setting->discount_percentage;
                
                if((float)$this->discount_amt > $max_dis)
                {
                    $this->showModal('error', 'Error', 'Maximum discount upto '.$this->setting->discount_percentage. '%');
                    return false;
                    
                }
                
                
            }
            else{
                    $max_dis = (($this->selling_price*$this->setting->discount_percentage)/100);

                    if((float)$this->discount_amt > $max_dis)
                    {
                        //dd('eeoedddddddddo');
                        $this->showModal('error', 'Error', 'Maximum discount upto '.$max_dis);
                        return false;
                    }
                }*/
            }
            else{
                $this->discount_amt =0;
                $this->discount_type =null;
            }

            $this->formSubmit =1;
        
        if($this->storeUser == 1)
        {
            $updateProduct = Product::find($this->product_id);
            $slug=Str::slug($this->name.'-');
            $chk=Product::where('id', '!=', $this->product_id)->where('name_slug',$slug)->first();
            if($chk){
                $slug=$slug."-".Product::where('name_slug',$slug)->count();
            }

           
            $updateProduct->update([
            'name' => $this->name,
            'is_discount' => $this->is_discount,
            'discount_type' => $this->discount_type,
            'discount_amt' => $this->discount_amt,
            /*'product_code' => $this->product_code,*/
            'quantity' => ($updateProduct->quantity+$this->quantity),
            'default_quantity' => $this->default_quantity,
            /*'selling_price' => $this->selling_price,
            'purchase_price' => $this->purchase_price,*/
            'name_slug' =>$slug,
        ]);

            ProductQuantity::create([
                'product_id' => $updateProduct->id,
                'quantity' => $this->quantity,
                'date' => date('Y-m-d'),
                'time' => date('Y-m-d H:i:s'),
            ]);
            $this->reset(['quantity', 'name', 'default_quantity', 'selling_price', 'purchase_price']);

            if (isset($this->image) && !is_string($this->image)) 
            {
                $product_img = $this->image;
                $filename = time() . '-' . rand(1000, 9999) . '.' . $product_img->getClientOriginalExtension();
                $product_img->storeAs("public/product_image", $filename);
                if(isset($updateProduct->gallery))
                {
                    @unlink(storage_path('app/public/product_image/' . $updateProduct->gallery->gallery_image));
                }
                Gallery::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            }
        }
        else{
           $updateProduct = Product2::find($this->product_id);
            $slug=Str::slug($this->name.'-');
            $chk=Product2::where('id', '!=', $this->product_id)->where('name_slug',$slug)->first();
            if($chk){
                $slug=$slug."-".Product2::where('name_slug',$slug)->count();
            }
            $updateProduct->update([
            'name' => $this->name,
            'is_discount' => $this->is_discount,
            'discount_type' => $this->discount_type,
            'discount_amt' => $this->discount_amt,
            /*'product_code' => $this->product_code,*/
            'quantity' => ($updateProduct->quantity+$this->quantity),
            'default_quantity' => $this->default_quantity,
            /*'selling_price' => $this->selling_price,
            'purchase_price' => $this->purchase_price,*/
            'name_slug' =>$slug,
        ]);

            ProductQuantity2::create([
                'product_id' => $updateProduct->id,
                'quantity' => $this->quantity,
                'date' => date('Y-m-d'),
                'time' => date('Y-m-d H:i:s'),
            ]);
            $this->reset(['quantity', 'name', 'default_quantity', 'selling_price', 'purchase_price']);

            if (isset($this->image) && !is_string($this->image)) 
            {
                $product_img = $this->image;
                $filename = time() . '-' . rand(1000, 9999) . '.' . $product_img->getClientOriginalExtension();
                $product_img->storeAs("public/product_image", $filename);
                if(isset($updateProduct->gallery))
                {
                    @unlink(storage_path('app/public/product_image/' . $updateProduct->gallery->gallery_image));
                }
                Gallery2::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            } 
        }

        
            $msgAction = 'Product Update Successfully';
            $this->showToastr("success",$msgAction);
            return redirect()->route('product_index');

        }else
        {
            
            $this->validate([
                'name' => 'required',
                'discount_type' => 'required_if:is_discount,true',
                'discount_amt' => 'required_if:is_discount,true',
                /*'product_code' => 'required|regex:/^[A-Z]{6,6}$/|unique:st_product',*/
                'quantity' => 'required',
                'default_quantity' => 'required',
                'purchase_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
            ]);

            if($this->is_discount)
            {
                /*if($this->discount_type == 'Percentage')
                {
                    $max_dis =(float)$this->setting->discount_percentage;
                    
                    if((float)$this->discount_amt > $max_dis)
                    {
                        $this->showModal('error', 'Error', 'Maximum discount upto '.$this->setting->discount_percentage. '%');
                        return false;
                        
                    }
                    
                    
                }
                else{
                    $max_dis = (($this->selling_price*$this->setting->discount_percentage)/100);

                    if((float)$this->discount_amt > $max_dis)
                    {
                        //dd('eeoedddddddddo');
                        $this->showModal('error', 'Error', 'Maximum discount upto '.$max_dis);
                        return false;
                    }
                }*/
            }
            else{
                $this->discount_amt =0;
                $this->discount_type =null;
            }
           
            $this->formSubmit =1;
            //dd($this->formSubmit);
            if($this->storeUser == 1)
            {
                while (true) {
                    $numSeed = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                        $shuffled = str_shuffle($numSeed);
                        $code  =  substr($shuffled,1,6);
                        $data = Product::where('product_code', $code)->count();
                        if($data == 0)
                        {
                            break;
                        }
                }
                $slug=Str::slug($this->name.'-');
                $chk=Product::where('name_slug',$slug)->first();
                if($chk){
                    $slug=$slug."-".Product::where('name_slug',$slug)->count();
                }

                $data = Product::create([
                    'name' => $this->name,
                    'is_discount' => $this->is_discount?$this->is_discount:0,
                    'discount_type' => $this->discount_type,
                    'discount_amt' => $this->discount_amt,
                    'product_code' => $code,
                    'quantity' => $this->quantity,
                    'default_quantity' => $this->default_quantity,
                    'selling_price' => $this->selling_price,
                    'purchase_price' => $this->purchase_price,
                    'post_date' => date('Y-m-d'),
                    'name_slug' =>$slug,
                ]);

                ProductQuantity::create([
                    'product_id' => $data->id,
                    'quantity' => $this->quantity,
                    'date' => date('Y-m-d'),
                    'time' => date('Y-m-d H:i:s'),
                ]);
                $this->reset(['quantity', 'name', 'default_quantity', 'selling_price', 'purchase_price']);

                if($this->image)
                {
                    $filename = time() . '-' . rand(1000, 9999) . '.' . $this->image->getClientOriginalExtension();
                    $this->image->storeAs("public/product_image", $filename);

                    $data->gallery()->create([
                        'gallery_image' => $filename,
                        'status' => 'Y',
                    ]);
                }
            }
            else
            {
                while (true) {
                $numSeed = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $shuffled = str_shuffle($numSeed);
                    $code  =  substr($shuffled,1,6);
                    $data = Product2::where('product_code', $code)->count();
                    if($data == 0)
                    {
                        break;
                    }
                }
                $slug=Str::slug($this->name.'-');
                $chk=Product2::where('name_slug',$slug)->first();
                if($chk){
                    $slug=$slug."-".Product2::where('name_slug',$slug)->count();
                }

                $data = Product2::create([
                    'name' => $this->name,
                    'is_discount' =>  $this->is_discount?$this->is_discount:0,
                    'discount_type' => $this->discount_type,
                    'discount_amt' => $this->discount_amt,
                    'product_code' => $code,
                    'quantity' => $this->quantity,
                    'default_quantity' => $this->default_quantity,
                    'selling_price' => $this->selling_price,
                    'purchase_price' => $this->purchase_price,
                    'post_date' => date('Y-m-d'),
                    'name_slug' =>$slug,
                ]);

                ProductQuantity2::create([
                    'product_id' => $data->id,
                    'quantity' => $this->quantity,
                    'date' => date('Y-m-d'),
                    'time' => date('Y-m-d H:i:s'),
                ]);
                $this->reset(['quantity', 'name', 'default_quantity', 'selling_price', 'purchase_price']);

                if($this->image)
                {
                    $filename = time() . '-' . rand(1000, 9999) . '.' . $this->image->getClientOriginalExtension();
                    $this->image->storeAs("public/product_image", $filename);

                    $data->gallery()->create([
                        'gallery_image' => $filename,
                        'status' => 'Y',
                    ]);
                }
            }

            $msgAction = 'Product Add Successfully';
            $this->showToastr("success",$msgAction);
            return redirect()->route('product_index');  
        }

    }

    public function viewOrders($id)
    {
//dd($id);
        if($this->storeUser == 1)
            $this->viewOrder = ProductOrderDetails::with('productDetails','returnProducts')->where('order_id',$id)->first();
        else
            $this->viewOrder = ProductOrderDetails2::with('productDetails','returnProducts')->where('order_id',$id)->first();
        
        //dd($this->viewOrder);
        $this->dispatchBrowserEvent('show-order-view-form');
    }

    public function viewProductData($id)
    {
        if($this->storeUser == 1)
            $this->viewProduct = Product::with('productQuantities','productReductions', 'returnProducts','productOrders.orderDetails')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->withSum('productReductions', 'qty')->find($id);
        else
            $this->viewProduct = Product2::with('productQuantities','productReductions', 'returnProducts','productOrders.orderDetails')->withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);
        //dd($this->viewProduct);

        $this->dispatchBrowserEvent('show-product-view-form');

    }

   
    public function editProduct($id)
    {
        if($this->storeUser == 1)
            $editProduct = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);
        else
            $editProduct = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);

        $this->state['id'] = $editProduct->id;
        $this->state['name'] = $editProduct->name;
        $this->state['product_code'] = $editProduct->product_code;
        $this->state['bar_code'] = $editProduct->bar_code;
        /*$this->state['quantity'] = $editProduct->quantity;*/

        $this->state['quantity'] = null;
        $this->state['current_quantity'] = $editProduct->product_quantities_sum_quantity-($editProduct->return_products_quantity_sum_qty+$editProduct->product_orders_sum_qty+$editProduct->productReductions->sum('qty'));
        $this->state['default_quantity'] = $editProduct->default_quantity;
        $this->state['selling_price'] = $editProduct->selling_price;
        $this->state['purchase_price'] = $editProduct->purchase_price;
        $this->state['is_discount'] = $editProduct->is_discount;
        $this->edit_is_discount = $editProduct->is_discount;
        $this->state['discount_type'] = $editProduct->discount_type;
        $this->state['discount_amt'] = $editProduct->discount_amt;
        $this->state['gallery_image'] = isset($editProduct->gallery)?$editProduct->gallery->gallery_image:null;
        $this->dispatchBrowserEvent('show-product-edit-form');

    }

    public function updateProduct()
    {
        Validator::make($this->state,[
            'name' => 'required',
            'discount_type' => 'required_if:edit_is_discount,true',
            'discount_amt' => 'required_if:edit_is_discount,true|max:10',
            /*'product_code' => 'required|regex:/^[A-Z]{6,6}$/|unique:st_product,product_code,'.$updateProduct->id,*/
            'quantity' => 'nullable',
            'default_quantity' => 'required',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ])->validate();
        

        if($this->edit_is_discount)
        {

        
            /*if($this->state['discount_type'] == 'Percentage')
            {
                $max_dis =(float)$this->setting->discount_percentage;
                
                if((float)$this->state['discount_amt'] > $max_dis)
                {
                    $this->showModal('error', 'Error', 'Maximum discount upto '.$this->setting->discount_percentage. '%');
                    return false;
                    
                }
                
                
            }
            else{
                $max_dis = (($this->state['selling_price']*$this->setting->discount_percentage)/100);

                if((float)$this->state['discount_amt'] > $max_dis)
                {
                    //dd('eeoedddddddddo');
                    $this->showModal('error', 'Error', 'Maximum discount upto '.$max_dis);
                    return false;
                }
            } */  
        }
        else{
            $this->state['discount_type'] = null;
            $this->state['discount_amt'] = 0;
        }

        if ($this->storeUser == 1)
        {
            $updateProduct = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($this->state['id']);
            $updateProductQuantity = $updateProduct->product_quantities_sum_quantity-($updateProduct->return_products_quantity_sum_qty+$updateProduct->product_orders_sum_qty+$updateProduct->productReductions->sum('qty'));
            if (isset($this->state['image']) && !is_string($this->state['image'])) {
                $product_img = $this->state['image'];
                $filename = time() . '-' . rand(1000, 9999) . '.' . $product_img->getClientOriginalExtension();
                $product_img->storeAs("public/product_image", $filename);
                if(isset($updateProduct->gallery))
                {
                    @unlink(storage_path('app/public/product_image/' . $updateProduct->gallery->gallery_image));
                }
                Gallery::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            }

            $slug=Str::slug($this->state['name'].'-');
            $chk=Product::where('id', '!=', $this->state['id'])->where('name_slug',$slug)->first();
            if($chk){
                $slug=$slug."-".Product::where('name_slug',$slug)->count();
            }
            $available_quantity =0;

            if(isset($this->state['quantity']))
            {
                if((int)$this->state['quantity'] == '0')
                {
                
                    $available_quantity = 0;
                    EditProductStock::create([
                        'product_id' => $updateProduct->id,
                        'pro_name' =>  $this->state['name'],
                        'qty' => $updateProductQuantity,
                        'date' => date('Y-m-d'),
                    ]); 
                }
                elseif((int)$this->state['quantity'] != '0')
                {
                    
                   EditProductStock::create([
                        'product_id' => $updateProduct->id,
                        'pro_name' =>  $this->state['name'],
                        'qty' => $updateProductQuantity-(int)$this->state['quantity'],
                        'date' => date('Y-m-d'),
                    ]);
                    $available_quantity = ($updateProductQuantity-(int)$this->state['quantity']); 
                }
            }
            else{
                $available_quantity = $updateProductQuantity;
            }
            /*if($this->state['quantity'] == '0')
            {
               EditProductStock::create([
                    'product_id' => $updateProduct->id,
                    'pro_name' =>  $this->state['name'],
                    'qty' => $updateProduct->quantity,
                    'date' => date('Y-m-d'),
                ]); 
            }*/
            


            $updateProduct->update([
                'name' => $this->state['name'],
                'is_discount' => $this->edit_is_discount,
                'discount_type' => $this->state['discount_type'],
                'discount_amt' => $this->state['discount_amt'],
                /*'product_code' => $this->state['product_code'],*/
                'default_quantity' => $this->state['default_quantity'],
                'quantity' => $available_quantity,
                'selling_price' => $this->state['selling_price'],
                'purchase_price' => $this->state['purchase_price'],
                'name_slug' =>$slug,
            ]);

        }
        else
        {
            $updateProduct = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($this->state['id']);
            $updateProductQuantity = $updateProduct->product_quantities_sum_quantity-($updateProduct->return_products_quantity_sum_qty+$updateProduct->product_orders_sum_qty+$updateProduct->productReductions->sum('qty'));
            if (isset($this->state['image']) && !is_string($this->state['image'])) {
                $product_img = $this->state['image'];
                $filename = time() . '-' . rand(1000, 9999) . '.' . $product_img->getClientOriginalExtension();
                $product_img->storeAs("public/product_image", $filename);
                if(isset($updateProduct->gallery))
                {
                    @unlink(storage_path('app/public/product_image/' . $updateProduct->gallery->gallery_image));
                }
                Gallery2::updateOrCreate([
                    'product_id' => $updateProduct->id
                    ],
                    [
                    'gallery_image' => $filename,
                    'status' => 'Y',
                ]);
            }

            $slug=Str::slug($this->state['name'].'-');
            $chk=Product2::where('id', '!=', $this->state['id'])->where('name_slug',$slug)->first();
            if($chk){
                $slug=$slug."-".Product2::where('name_slug',$slug)->count();
            }
            
              $available_quantity =0;
            if(isset($this->state['quantity']))
            {
                if((int)$this->state['quantity'] == '0')
                {
                
                    $available_quantity = 0;
                    EditProductStock2::create([
                        'product_id' => $updateProduct->id,
                        'pro_name' =>  $this->state['name'],
                        'qty' => $updateProductQuantity,
                        'date' => date('Y-m-d'),
                    ]); 
                }
                elseif($updateProductQuantity != (int)$this->state['quantity'])
                {
                    
                   EditProductStock2::create([
                        'product_id' => $updateProduct->id,
                        'pro_name' =>  $this->state['name'],
                        'qty' => $updateProductQuantity-(int)$this->state['quantity'],
                        'date' => date('Y-m-d'),
                    ]);
                    $available_quantity = ($updateProductQuantity-(int)$this->state['quantity']); 
                }
            }
            else{
                $available_quantity = $updateProductQuantity;
            }
            /*if($this->state['quantity'] == '0')
            {
               EditProductStock::create([
                    'product_id' => $updateProduct->id,
                    'pro_name' =>  $this->state['name'],
                    'qty' => $updateProduct->quantity,
                    'date' => date('Y-m-d'),
                ]); 
            }*/
            


            $updateProduct->update([
                'name' => $this->state['name'],
                'is_discount' => $this->edit_is_discount,
                'discount_type' => $this->state['discount_type'],
                'discount_amt' => $this->state['discount_amt'],
                /*'product_code' => $this->state['product_code'],*/
                'default_quantity' => $this->state['default_quantity'],
                'quantity' => $available_quantity,
                'selling_price' => $this->state['selling_price'],
                'purchase_price' => $this->state['purchase_price'],
                'name_slug' =>$slug,
            ]);

            
        }

       
        $msgAction = 'Product Update Successfully';
        $this->showToastr("success",$msgAction);

        return redirect()->route('product_index');
    }

    
    

    public function deleteAttempt($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "You won't be able to recover this Product!", 'Yes, delete!', 'deleteConfirm', ['id' => $id]);
    }

    public function deleteConfirm($id)
    {   if ($this->storeUser == 1)
            $deleteProduct = Product::find($id['id']);
        else
            $deleteProduct = Product2::find($id['id']);

        if(count($deleteProduct->productOrders)>0 || count($deleteProduct->returnProducts)>0)
        {
            $this->showToastr("error","You can't delete this product", false);
        }
        else{
            if(isset($deleteProduct->gallery))
            {
                @unlink(storage_path('app/public/product_image/'.$deleteProduct->gallery->gallery_image));
                $deleteProduct->gallery()->delete();
            }
            $deleteProduct->delete();
            $this->showToastr("success","Product has been deleted successfully", false);
        }
        //$this->showModal('success', 'Success', 'Product has been deleted successfully');
    }

    public function decrementQuantity($id, $value)
    {
        if($this->storeUser == 1)
            $changeProduct = Product::find($id);
        else
            $changeProduct = Product2::find($id);

        if($this->cart_quantity[$value] == '1')
        {
            $this->showModal('error', 'Error', 'Quantity should be greater equal to 1');
        }
        else{
            if($this->cart_quantity[$value])
                $this->cart_quantity[$value] = $this->cart_quantity[$value]-1;
        }

    }

    public function incrementQuantity($id, $value)
    {
        if($this->storeUser == 1)
            $changeProduct = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);
        else
            $changeProduct = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);

        $changeProductQuantity = $changeProduct->product_quantities_sum_quantity-($changeProduct->return_products_quantity_sum_qty+$changeProduct->product_orders_sum_qty+$changeProduct->productReductions->sum('qty'));
        if($changeProductQuantity <= $this->cart_quantity[$value])
        {
            $this->showModal('error', 'Error', 'Quantity should be less equal to product quantity');
        }
        else{
            if($this->cart_quantity[$value])
                $this->cart_quantity[$value] = $this->cart_quantity[$value]+1;
        }
    }

    public function addToCart($id, $key)
    {
        //dd('okk');
        if($this->storeUser == 1)
        {
            $changeProduct = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);

            $changeProductQuantity = $changeProduct->product_quantities_sum_quantity-($changeProduct->return_products_quantity_sum_qty+$changeProduct->product_orders_sum_qty+$changeProduct->productReductions->sum('qty'));
            if($changeProductQuantity < 1)
            {
                $this->showModal('error', 'Error', 'Quantity should be less equal to product quantity');
            }
            else{
                $cartProduct = Product::select('id', 'quantity', 'selling_price')->find($id);
                $alreadycart = CartItem::where('product_id', $id)->where('user_id', Auth::user()->id)->first();
                if(isset($alreadycart))
                {
                    $this->showModal('error', 'Error', 'Already added in cart');
                }else
                {
                    $discount_amount = 0;
                    $total_discount = 0;
                    if(isset($this->discount[$key]) && $this->discount[$key] !="" && is_numeric($this->discount[$key]))
                    {
                        $discount_amount = $this->discount[$key];
                        $total_discount = $this->discount[$key]*$this->cart_quantity[$key];
                    }
                    $cart = CartItem::create([
                        'product_id' => $cartProduct->id,
                        'customer_phone' => 0,
                        'available_qty' => $cartProduct->quantity,
                        'quantity' => $this->cart_quantity[$key],
                        'selling_price' => $cartProduct->selling_price,
                        'discount' => $discount_amount,
                        'total_discount' => $total_discount,
                        'user_id' => Auth::user()->id,
                    ]);
                    $this->count_cart_item = CartItem::where('user_id', Auth::user()->id)->count();
                    if($cart)
                    {
                        $this->cart_count = CartItem::count();
                        $this->showToastr('success', 'Add To Cart Successfully', false);
                    }
                    else
                        $this->showToastr('error', 'Something Went Wrong', false);
                }
            }
        }
        else
        {
            $changeProduct = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($id);

            $changeProductQuantity = $changeProduct->product_quantities_sum_quantity-($changeProduct->return_products_quantity_sum_qty+$changeProduct->product_orders_sum_qty+$changeProduct->productReductions->sum('qty'));
            if($changeProductQuantity < 1)
            {
                $this->showModal('error', 'Error', 'Quantity should be less equal to product quantity');
            }
            else{
                $cartProduct = Product2::select('id', 'quantity', 'selling_price')->find($id);
                $alreadycart = CartItem2::where('product_id', $id)->where('user_id', Auth::user()->id)->first();
                if(isset($alreadycart))
                {
                    $this->showModal('error', 'Error', 'Already added in cart');
                }else
                {
                    $discount_amount = 0;
                    $total_discount = 0;
                    if(isset($this->discount[$key]) && $this->discount[$key] !="" && is_numeric($this->discount[$key]))
                    {
                        $discount_amount = $this->discount[$key];
                        $total_discount = $this->discount[$key]*$this->cart_quantity[$key];
                    }
                    $cart = CartItem2::create([
                        'product_id' => $cartProduct->id,
                        'customer_phone' => 0,
                        'available_qty' => $cartProduct->quantity,
                        'quantity' => $this->cart_quantity[$key],
                        'selling_price' => $cartProduct->selling_price,
                        'discount' => $discount_amount,
                        'total_discount' => $total_discount,
                        'user_id' => Auth::user()->id,
                    ]);
                    $this->count_cart_item = CartItem2::where('user_id', Auth::user()->id)->count();
                    if($cart)
                    {
                        $this->cart_count = CartItem2::count();
                        $this->showToastr('success', 'Add To Cart Successfully', false);
                    }
                    else
                        $this->showToastr('error', 'Something Went Wrong', false);
                }
            }
        }
    }
}
