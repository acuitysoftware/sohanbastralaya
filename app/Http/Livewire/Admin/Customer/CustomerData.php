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

class CustomerData extends Component
{
    use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $card_details, $total_credit_points, $perPage, $returnOrder, $setting,$memberships=[],$dateForm, $dateTo;
    public $searchName, $searchPhone, $searchCard, $customer_details =[], $orderDetails, $viewOrder=[], $expiry_date_count,$perNo, $storeUser;
    protected $paginationTheme = 'bootstrap';
	protected $listeners = ['viewCustomer', 'loadMore', 'customerDetails'];

	public function mount()
    {
        $this->perNo = request()->perNo??env('PER_PAGE', '50');
        $this->perPage =$this->perNo;
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
    public function updatedStoreUser($value)
    {
        Session::put('store', $value);
    }
    public function loadMore()
    {
        $this->perPage= $this->perPage+20;
    }

    public function resetSearch()
    {
       $this->searchPhone = null;
       $this->searchCard = null;
    }

    public function exportCsv()
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="users.csv"',
    ];

    if($this->storeUser == 1)
    {
            
        $customerQuery = ProductOrderDetails::select('st_product_order_details.customer_name as customer_name','st_product_order_details.customer_phone as customer_phone','st_product_order_details.order_date as order_date', DB::raw("count(*) as count"),DB::raw('sum(st_product_order_details.subtotal) AS total_amount'))->where('customer_phone', '!=', "0")->groupBy('customer_phone')->orderBy('total_amount', 'desc'); // Fetch your data
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        $orders = $customerQuery->get();
    }
    else{
        $customerQuery = ProductOrderDetails2::select('st_product_order_details.customer_name as customer_name','st_product_order_details.customer_phone as customer_phone','st_product_order_details.order_date as order_date', DB::raw("count(*) as count"),DB::raw('sum(st_product_order_details.subtotal) AS total_amount'))->where('customer_phone', '!=', "0")->groupBy('customer_phone')->orderBy('total_amount', 'desc');
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        $orders = $customerQuery->get();
    }

    $callback = function() use ($orders) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Order ID', 'Order Date', 'Biil Amount']); // Add column headers

        foreach ($orders as $order) {
            fputcsv($file, [$order->order_id, $order->order_date, $order->total_amount]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
   

    public function render()
    {
        if($this->storeUser == 1){
           /*  $customerQuery = ProductOrderDetails::select('st_product_order_details.customer_name as customer_name','st_product_order_details.customer_phone as customer_phone','st_product_order_details.order_date as order_date', DB::raw("count(*) as count"),DB::raw('sum(st_product_order_details.subtotal) AS total_amount'))->where('customer_phone', '!=', "0")->groupBy('customer_phone'); */
            $customerQuery = ProductOrderDetails::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')->select('st_product_order_details.customer_name as customer_name','st_product_order_details.customer_phone as customer_phone','st_product_order_details.order_date as order_date', DB::raw("count(*) as count"),DB::raw('CAST(sum(st_product_order.qty*st_product_order.selling_price)-sum(st_product_order.qty*st_product_order.discount)  AS FLOAT) as total_amount'),DB::raw('sum(st_product_order.qty*st_product_order.discount) AS total_discount'))->where('customer_phone', '!=', "0")->groupBy('customer_phone');
            if ($this->searchPhone)
            {
                $phone = $this->searchPhone;
               $customerQuery->where(function($q)use($phone){
                $q->where('customer_name', 'like', '%' . trim($phone) . '%')->orWhere('customer_phone', 'like', '%' . trim($phone) . '%');
               });
            }
            if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
            
           $customerQuery = $customerQuery->orderBy('total_amount', 'desc')->paginate($this->perPage);
            //$customerQuery = $customerQuery->orderBy('total_amount', 'desc')->paginate(10);


        }
        else{
             $customerQuery = ProductOrderDetails2::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')->select('st_product_order_details.customer_name as customer_name','st_product_order_details.customer_phone as customer_phone','st_product_order_details.order_date as order_date', DB::raw("count(*) as count"),DB::raw('CAST(sum(st_product_order.qty*st_product_order.selling_price)-sum(st_product_order.qty*st_product_order.discount)  AS FLOAT) as total_amount'),DB::raw('sum(st_product_order.qty*st_product_order.discount) AS total_discount'))->where('customer_phone', '!=', "0")->groupBy('customer_phone');
            if ($this->searchPhone)
            {
               $phone = $this->searchPhone;
               $customerQuery->where(function($q)use($phone){
                $q->where('customer_name', 'like', '%' . trim($phone) . '%')->orWhere('customer_phone', 'like', '%' . trim($phone) . '%');
               });
            }
            if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $customerQuery = $customerQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
            
             $customerQuery = $customerQuery->orderBy('total_amount', 'desc')->paginate($this->perPage);
            
        }
        
        
        return view('livewire.admin.customer.customer-data', [
            'customers' => $customerQuery
        ]);
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

    public function viewCustomer($value)
    {
        $today = date('Y-m-d');
        if($this->storeUser == 1)
        {
            $this->customer_details = ProductOrderDetails::with('productDetails', 'card','cards')->where('customer_phone', $value)->orderBy('id', 'desc')->get();
            $this->card_details = Membership::with('order')->where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
            $credit_points = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
            $total_debit_point = Membership::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
            $this->total_credit_points = $credit_points-$total_debit_point;
            $this->dispatchBrowserEvent('show-customer-details');
        }
        else{
            $this->customer_details = ProductOrderDetails2::with('productDetails', 'card','cards')->where('customer_phone', $value)->orderBy('id', 'desc')->get();
            $this->card_details = Membership2::with('order')->where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id')->first();
            $credit_points = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('credit_points');
            $total_debit_point = Membership2::where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->sum('debit_point');
            $this->total_credit_points = $credit_points-$total_debit_point;
            //dd($this->total_credit_points);
            $this->dispatchBrowserEvent('show-customer-details');
        }
    }

    public function viewOrder($id)
    {
        if($this->storeUser == 1)
        {
            $this->orderDetails = ProductOrderDetails::with('productDetails')->withCount('due_payments')->where('order_id',$id)->first();
            $this->viewOrder = ProductOrder::with('customer')->where('order_id',$id)->get();
        }
        else{

            $this->orderDetails = ProductOrderDetails2::with('productDetails')->withCount('due_payments')->where('order_id',$id)->first();
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
            return redirect()->route('customer.index');

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
            return redirect()->route('customer.index');
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
