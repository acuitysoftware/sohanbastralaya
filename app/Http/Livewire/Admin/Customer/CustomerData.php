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
	protected $listeners = ['viewCustomer', 'loadMore', 'customerDetails'];

	public function mount()
    {
        $this->perNo = request()->perNo??200;
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
        $this->perPage= $this->perPage+200;
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
            
        $customerQuery = ProductOrderDetails::select('order_id','customer_phone','order_date', 'subtotal')->orderBy('order_id', 'desc'); // Fetch your data
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
        $customerQuery = ProductOrderDetails2::select('order_id','customer_phone','order_date', 'subtotal')->orderBy('order_id', 'desc'); // Fetch your data
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
            fputcsv($file, [$order->order_id, $order->order_date, $order->subtotal]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
   

    public function render()
    {
        if($this->storeUser == 1){
            $customerQuery = ProductOrderDetails::select('customer_name','customer_phone','order_date');
            if ($this->searchPhone)
            {
               $customerQuery = $customerQuery->where('customer_name', 'like', '%' . trim($this->searchPhone) . '%')->orWhere('customer_phone', 'like', '%' . trim($this->searchPhone) . '%');
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
            
            $customerQuery = $customerQuery->orderBy('order_date', 'desc')->groupBy('customer_phone')->paginate($this->perPage);


        }
        else{
            $customerQuery = ProductOrderDetails2::select('customer_name','customer_phone','order_date');
            if ($this->searchPhone)
            {
               $customerQuery = $customerQuery->where('customer_name', 'like', '%' . trim($this->searchPhone) . '%')->orWhere('customer_phone', 'like', '%' . trim($this->searchPhone) . '%');
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
            
             $customerQuery = $customerQuery->orderBy('order_date', 'desc')->groupBy('customer_phone')->paginate($this->perPage);
            
        }
        
        
        return view('livewire.admin.customer.customer-data', [
            'customers' => $customerQuery
        ]);
    }
    
}
