<?php

namespace App\Http\Livewire\Admin\Customer;

use DB;
use Hash;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Product2;
use App\Models\ReturnProduct;
use App\Models\ReturnProduct2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use App\Models\Membership;
use App\Models\Membership2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class CustomerView extends Component
{

	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $card_details, $total_credit_points, $perPage, $returnOrder, $setting,$memberships=[];
    public $searchName, $customer_details =[], $orderDetails, $viewOrder=[], $expiry_date_count, $storeUser, $contact;
	protected $listeners = ['viewCustomer', 'loadMore', 'customerDetails'];
    protected $paginationTheme = 'bootstrap';
	public function mount($contact)
	{
		$this->contact = $contact;
		$this->perPage =env('PER_PAGE', 50);
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

	public function loadMore()
    {
        $this->perPage= $this->perPage+env('PER_PAGE', 50);
    }
    public function render()
    {
    	$today = date('Y-m-d');
        if($this->storeUser == 1)
        {
            $this->customer_details = ProductOrderDetails::with('productDetails', 'card','cards')->where('customer_phone', $this->contact)->orderBy('id', 'desc')->get();
            $this->card_details = Membership::with('order')->where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
            $credit_points = Membership::where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
            $total_debit_point = Membership::where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
            $this->total_credit_points = $credit_points-$total_debit_point;
        }
        else{
            $this->customer_details = ProductOrderDetails2::with('productDetails', 'card','cards')->where('customer_phone', $this->contact)->orderBy('id', 'desc')->get();
            $this->card_details = Membership2::with('order')->where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
            $credit_points = Membership2::where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
            $total_debit_point = Membership2::where('contact',$this->contact)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
            $this->total_credit_points = $credit_points-$total_debit_point;
        }
        return view('livewire.admin.customer.customer-view');
    }

    public function viewmemberships($value)
    {
        $today = date('Y-m-d');
        if($this->storeUser == 1)
        {
            $this->memberships = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc')->get();
            if(count($this->memberships))
            {
                $data = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
                $date1 = date_create($today);
                $date2 = date_create($data->expiry_date);
                $diff = date_diff($date1,$date2);
                $this->expiry_date_count = $diff->format("%a");

                $credit_points = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
                $total_debit_point = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
                $this->total_credit_points = $credit_points-$total_debit_point;
                $this->dispatchBrowserEvent('show-membership-details');
            }
            else{
                $this->showModal('error', 'Error', 'No Card number is saved');
            }
        }
        else{
            $this->memberships = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc')->get();
            if(count($this->memberships))
            {
                $data = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
                $date1 = date_create($today);
                $date2 = date_create($data->expiry_date);
                $diff = date_diff($date1,$date2);
                $this->expiry_date_count = $diff->format("%a");

                $credit_points = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
                $total_debit_point = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
                $this->total_credit_points = $credit_points-$total_debit_point;
                $this->dispatchBrowserEvent('show-membership-details');
            }
            else{
                $this->showModal('error', 'Error', 'No Card number is saved');
            }
        }
        
    }

    public function viewOrder($id)
    {
        if($this->storeUser == 1)
        {
            $this->orderDetails = ProductOrderDetails::with('productDetails')->where('order_id',$id)->first();
            $this->viewOrder = ProductOrder::with('customer')->where('order_id',$id)->get();
        }
        else{

            $this->orderDetails = ProductOrderDetails2::with('productDetails')->where('order_id',$id)->first();
            $this->viewOrder = ProductOrder2::with('customer')->where('order_id',$id)->get();
        }
        $this->dispatchBrowserEvent('show-order-view-form');
    }

    public function saveReturnOrder()
    {
        if($this->storeUser == 1){
            $order = ProductOrder::with('customer')->find($this->return_order_id);
            $product = Product::find($order->product_id);
        }
        else{
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
                'price' => ($data->price+(($order->selling_price*$this->product_qty)-($order->discount*$this->product_qty))),
                'customer_contact' => $order->customer->customer_phone,
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
                $order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);
                $order->delete();

            }
            $this->showToastr("success",'Product retured successfully');
            return redirect()->route('customer_list');

        }else
        {
            if($this->storeUser == 1){
                $data = ReturnProduct::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id]);
            }else{
               $data = ReturnProduct2::firstOrCreate ([
                    'order_id' => $order->order_id,
                    'product_id' => $order->product_id]); 
            }

            $data->update([
                'product_name' => $order->product_name,
                'product_code' => $order->product_code,
                'qty' => ($data->qty+$this->product_qty),
                'selling_price' => $order->selling_price,
                'discount' => $order->discount,
                'purchase_price' => $order->purchase_price,
                'price' => ($data->price+(($order->selling_price*$this->product_qty)-($order->discount*$this->product_qty))),
                'customer_contact' => $order->customer->customer_phone,
                'status' => 'active',
            ]);
            if($data)
            {
                if($this->storeUser == 1)
                    $product = Product::find($order->product_id);
                else
                    $product = Product::find($order->product_id);

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
                $order->customer()->update([
                    'subtotal' => ($order->customer->subtotal-($order->selling_price*$this->product_qty)),
                    'discount_amt' => $discount_amt,
                    'perctge_amt' => $perctge_amt,
                    'total_amount' => ($order->customer->total_amount-(($order->selling_price-$order->discount)*$this->product_qty)),

                ]);
            }
            $this->showToastr("success",'Product retured successfully');
            return redirect()->route('customer_list');
        }
    }

    public function returnOrder($id)
    {
        if($this->storeUser == 1)
            $this->returnOrder = ProductOrder::with('customer')->find($id);
        else
            $this->returnOrder = ProductOrder::with('customer')->find($id);

        $this->return_order_id = $this->returnOrder->id;
        $this->product_name = $this->returnOrder->product_name;
        $this->product_code = $this->returnOrder->product_code;
        $this->product_qty = $this->returnOrder->qty;
        $this->product_selling_price = $this->returnOrder->selling_price;

        $this->dispatchBrowserEvent('show-return-product-form');
    }
}
