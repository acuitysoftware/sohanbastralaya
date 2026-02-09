<?php

namespace App\Http\Livewire\Admin\Product;

use Str;
use Auth;
use Hash;
use Session;
use Validator;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Product2;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use App\Models\ReturnProduct;
use App\Models\ReturnProduct2;
use App\Models\ProductQuantity;
use App\Models\ProductQuantity2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use Livewire\Component;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class OrderView extends Component
{
    use WithSorting;
    use AlertMessage;

    public $setting, $storeUser, $order_id, $returnOrder, $product_name, $total_profit, $product_code, $product_qty, $product_selling_price, $return_order_id;
    protected $paginationTheme = 'bootstrap';
    public function mount($order_id)
    {   
        $this->order_id =$order_id;
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
        


    }
    public function render()
    {
    	if($this->storeUser == 1)
            $viewOrder = ProductOrderDetails::with('productDetails','returnProducts')->where('order_id',$this->order_id)->first();
        else
            $viewOrder = ProductOrderDetails2::with('productDetails','returnProducts')->where('order_id',$this->order_id)->first();


        return view('livewire.admin.product.order-view',['viewOrder' => $viewOrder]);
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
                    'quantity' => ($product->quantity+(int)$this->product_qty),
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
                /*$order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);*/
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

                $product->update(['quantity' => ($product->quantity+(int)$this->product_qty)]);
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
                /*$order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);*/
            }
            $this->showToastr("success",'Product retured successfully');
            return redirect()->route('product.order', $this->order_id);
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
}
