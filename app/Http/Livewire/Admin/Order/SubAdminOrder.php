<?php

namespace App\Http\Livewire\Admin\Order;

use DB;
use Auth;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class SubAdminOrder extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $dateForm, $dateTo, $orders=[];
    public $total_selling, $total_purchase, $total_purchase_price, $total_discount, $discount_percentage, $profit_percentage, $total_profit, $storeUser;
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
        
        
    }
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
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
       $this->dateForm = null;
       $this->dateTo = null;
    }

    public function viewOrder($id)
    {
         if ($this->storeUser == '1')
        {
            $this->viewOrder = ProductOrder::with('customer')->find($id);
        }else{
            $this->viewOrder = ProductOrder2::with('customer')->find($id);

        }
        $this->dispatchBrowserEvent('show-subadmin-order-details');
    }

    public function viewOrders($id)
    {
        if ($this->storeUser == '1')
        {
        $orderQuery = ProductOrderDetails::with('customer')->where('billing_user',$id);
        }
        else{

            $orderQuery = ProductOrderDetails2::with('customer')->where('billing_user',$id);
        }

        if ($this->dateForm && $this->dateTo)
    	{
    		$date['form_date'] = $this->dateForm;
    		$date['to_date'] = $this->dateTo;
           $orderQuery->whereHas("customer", function ($query) use ($date) {
				$query->whereBetween('order_date',[$date['form_date'],$date['to_date']]);
			});
    	}
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $orderQuery->whereHas("customer", function ($query) use ($date) {
                $query->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
            });
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $orderQuery->whereHas("customer", function ($query) use ($date) {
                $query->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
            });
        }
        $this->orders = $orderQuery->orderBy('id', 'desc')->get();
        
        $this->dispatchBrowserEvent('show-subadmin-orders');
    }

    public function saveReturnOrder()
    {
         if ($this->storeUser == '1')
        {
            $order = ProductOrder::find($this->return_order_id);
        }else{
            $order = ProductOrder2::find($this->return_order_id);

        }
        //dd($this->product_qty);
        $this->validate([
            'product_qty' => 'required|integer|between:1,'.$order->qty
        ],['product_qty.between' =>'Enter valid quantity']);
    }

    public function returnOrder($id)
    {
        if ($this->storeUser == '1')
        {
            $this->returnOrder = ProductOrder::with('customer')->find($id);
        }else{
            $this->returnOrder = ProductOrder2::with('customer')->find($id);

        }
        $this->return_order_id = $this->returnOrder->id;
        $this->product_name = $this->returnOrder->product_name;
        $this->product_code = $this->returnOrder->product_code;
        $this->product_qty = $this->returnOrder->qty;
        $this->product_selling_price = $this->returnOrder->selling_price;

        $this->dispatchBrowserEvent('show-return-product-form');
    }

    public function render()
    {
        
        if ($this->storeUser == '1')
        {
        	$userQuery = User::where('store', 1)->where(['type'=> 'S', 'status' =>1])->with('orders')->withCount('orders')->having('orders_count', '>', 0);
        }
        else{
            $userQuery = User::where('store', $this->storeUser)->where(['type'=> 'S', 'status' =>1])->with('orders_new_db')->withCount('orders_new_db')->having('orders_new_db_count', '>', 0);

        }
        //dd($userQuery->get());
    	return view('livewire.admin.order.sub-admin-order', [
            'users' => $userQuery->get()
            ]);
    }
}
