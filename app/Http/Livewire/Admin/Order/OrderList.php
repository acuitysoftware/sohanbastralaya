<?php

namespace App\Http\Livewire\Admin\Order;

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
use App\Http\Livewire\Traits\AlertMessage;

class OrderList extends Component
{

    use WithPagination;
    use AlertMessage;
    public $searchName, $dateForm, $dateTo, $viewOrder, $product_name, $total_profit, $product_code, $product_qty, $product_selling_price, $return_order_id,$perPage, $total_purchase, $returnOrder, $setting, $storeUser, $user;
    protected $listeners = ['deleteConfirm','loadMore'];
protected $paginationTheme = 'bootstrap';
    public function mount()
    {
        $this->perPage =env('PER_PAGE', 50);
        $this->dateForm =date('Y-m-d');
        $this->dateTo =date('Y-m-d');
        $this->setting = Setting::first();
        $this->user = Auth::user();
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

    public function viewOrders($id)
    {
        if($this->storeUser == 1)
            $this->viewOrder = ProductOrderDetails::with('productDetails','returnProducts')->find($id);
        else
            $this->viewOrder = ProductOrderDetails2::with('productDetails','returnProducts')->find($id);
        
        //dd($this->viewOrder);
        $this->dispatchBrowserEvent('show-order-view-form');
    }

    public function saveReturnOrder()
    {
        if($this->storeUser == 1){
            $order = ProductOrder::with('customer')->find($this->return_order_id);
            $product = Product::find($order->product_id);
        }else{
            $order = ProductOrder2::with('customer')->find($this->return_order_id);
            $product = Product2::find($order->product_id);
        }
        
        
        $this->validate([
            'product_qty' => 'required|integer|between:1,'.$order->qty
        ],['product_qty.between' =>'Enter valid quantity']);
        
        if($order->qty == $this->product_qty)
        {
            if($this->storeUser == 1){
                $data = ReturnProduct::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id
                ]);
            }
            else{
                $data = ReturnProduct2::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id
                ]);
            }

            $data->update([
                'product_name' => $order->product_name,
                'product_code' => $order->product_code,
                'qty' => ($data->qty+$order->qty),
                'selling_price' => $order->selling_price,
                'discount' => $order->discount,
                'purchase_price' => $order->purchase_price,
                'price' => ($order->selling_price*$this->product_qty),
                /*'price' => ($data->price+(($order->selling_price*$this->product_qty)-($order->discount*$this->product_qty))),*/
                'customer_contact' => $order->customer->customer_phone,
                'date' => date('Y-m-d'),
                'status' => 'active',
            ]);
            if($data)
            {
                $product->update([
                    'quantity' => ($product->quantity+$this->product_qty),
                ]);
                $discount_amt = 0.00;
                $perctge_amt = 0.00;
                if($order->customer->discount_amt != '0.00')
                {
                    $discount_amt = $order->customer->discount_amt-($order->discount*$this->product_qty);
                }
                if($order->customer->perctge_amt != '0.00')
                {
                    $perctge_amt = $order->customer->perctge_amt-($order->discount*$this->product_qty);
                }
                $order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);
                $order->delete();

            }
            $this->showToastr("success",'Product retured successfully');
            return redirect()->route('order_list');

        }else
        {
            if($this->storeUser == 1){
                $data = ReturnProduct::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id
                ]);
            }else{
                $data = ReturnProduct2::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id
                ]);
            }


            $data->update([
                'product_name' => $order->product_name,
                'product_code' => $order->product_code,
                'qty' => ($data->qty+$this->product_qty),
                'selling_price' => $order->selling_price,
                'discount' => $order->discount,
                'purchase_price' => $order->purchase_price,
                'price' => ($order->selling_price*$this->product_qty),
                /*'price' => ($data->price+(($order->selling_price*$this->product_qty)-($order->discount*$this->product_qty))),*/
                'customer_contact' => $order->customer->customer_phone,
                'status' => 'active',
            ]);
            if($data)
            {
                if($this->storeUser == 1)
                    $product = Product::find($order->product_id);
                else
                    $product = Product2::find($order->product_id);

                $product->update(['quantity' => ($product->quantity+$this->product_qty)]);
                $order->update([
                    'qty' => $order->qty-$this->product_qty,
                    'subtotal' => $order->selling_price*($order->qty-$this->product_qty),
                ]);
                $discount_amt = 0.00;
                $perctge_amt = 0.00;
                if($order->customer->discount_amt != '0.00')
                {
                    $discount_amt = $order->customer->discount_amt-($order->discount*$this->product_qty);
                }
                if($order->customer->perctge_amt != '0.00')
                {
                    $perctge_amt = $order->customer->perctge_amt-($order->discount*$this->product_qty);
                }
                $order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);
            }
            $this->showToastr("success",'Product retured successfully');
            return redirect()->route('order_list');
        }
    }

    public function returnOrder($id)
    {
        if($this->storeUser == 1)
            $this->returnOrder = ProductOrder::with('customer')->find($id);
        else
            $this->returnOrder = ProductOrder2::with('customer')->find($id);

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
            $orderQuery = ProductOrderDetails::with('productDetails', 'user');
            $totalOrderQuery = ProductOrderDetails::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')
            ->select('order_date',DB::raw("SUM(st_product_order_details.subtotal) as 'total_selling_price'"),DB::raw("SUM(cast(st_product_order_details.wallet_discount as decimal(10,2))) as 'wallet_blance'"),DB::raw("sum(st_product_order_details.discount_amt) as 'total_discount_amt'"),DB::raw("sum(st_product_order_details.perctge_amt) as 'total_discount_percnt'"),DB::raw("SUM(st_product_order_details.return_amt) as 'total_return_price'"),DB::raw('sum(st_product_order.qty*st_product_order.purchase_price) AS total_purchase_price'), DB::raw('count(st_product_order_details.order_id) AS count'));
        }
        else{
            $orderQuery = ProductOrderDetails2::with('productDetails', 'user');
            $totalOrderQuery = ProductOrderDetails2::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')
            ->select('order_date',DB::raw("SUM(st_product_order_details.subtotal) as 'total_selling_price'"),DB::raw("SUM(cast(st_product_order_details.wallet_discount as decimal(10,2))) as 'wallet_blance'"),DB::raw("sum(st_product_order.qty*st_product_order.discount) as 'total_discount'"),DB::raw("SUM(st_product_order_details.return_amt) as 'total_return_price'"),DB::raw('sum(st_product_order.qty*st_product_order.purchase_price) AS total_purchase_price'), DB::raw('count(st_product_order.order_id) AS count'));
        }
        /*if($this->dateForm == null && $this->dateTo == null)
        {
            $date['form_date'] = date('Y-m-d');
            $date['to_date'] = date('Y-m-d');
            $orderQuery = $orderQuery->whereBetween('order_date',[$date['form_date'],$date['to_date']]);
           $totalOrderQuery = $totalOrderQuery->whereBetween('order_date',[$date['form_date'],$date['to_date']]);
        }*/
        if ($this->dateForm && $this->dateTo)
        {
            $date['form_date'] = $this->dateForm;
            $date['to_date'] = $this->dateTo;
           $orderQuery = $orderQuery->whereBetween('order_date',[$date['form_date'],$date['to_date']]);
           $totalOrderQuery = $totalOrderQuery->whereBetween('order_date',[$date['form_date'],$date['to_date']]);
        }
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $orderQuery = $orderQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
            $totalOrderQuery = $totalOrderQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $orderQuery = $orderQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
            $totalOrderQuery = $totalOrderQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        $this->total_purchase = $totalOrderQuery->first();
        
        //dd($this->total_purchase );
        return view('livewire.admin.order.order-list', [
            'orders' => $orderQuery
                ->orderBy('id', 'desc')
                ->paginate($this->perPage)
        ]);
    }
    public function deleteAttempt($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "You won't be able to recover this Order!", 'Yes, delete!', 'deleteConfirm', ['id' => $id]);
    }

    public function deleteConfirm($id)
    {
        if($this->storeUser == 1)
            $deleteOrder = ProductOrderDetails::with('productDetails')->find($id['id']);
        else
            $deleteOrder = ProductOrderDetails2::with('productDetails')->find($id['id']);

        if(count($deleteOrder->productDetails))
        {
            foreach ($deleteOrder->productDetails as $key => $value) {
                $product = Product::find($value->product_id);
                if($product){
                    $product->update([
                        'quantity' => ($product->quantity+$value->qty),
                    ]);
                }
                $value->delete();
            }
        }
        if(count($deleteOrder->cards))
        {
            foreach ($deleteOrder->cards as $key => $value) {
                $value->delete();
            }
        }
        $deleteOrder->delete();
        $this->showModal('success', 'Success', 'Order has been deleted successfully');
    }
}
