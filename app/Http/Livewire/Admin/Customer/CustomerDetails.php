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

class CustomerDetails extends Component
{
    use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $card_details, $total_credit_points, $perPage, $returnOrder, $setting,$memberships=[],$dateForm, $dateTo;
    public $searchName, $customer_details =[], $orderDetails, $viewOrder=[], $expiry_date_count, $storeUser, $contact,$perNo;
	protected $listeners = ['viewCustomer', 'loadMore', 'customerDetails'];
    protected $paginationTheme = 'bootstrap';
	public function mount($contact)
	{
        $this->perNo = request()->perNo??env('PER_PAGE', '50');
		$this->contact = $contact;
		$this->perPage =env('PER_PAGE', '50');
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
        $this->perPage= $this->perPage+env('PER_PAGE', '50');
    }

     public function exportCsv()
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="users.csv"',
    ];

    if($this->storeUser == 1)
    {
            
        $orders = ProductOrderDetails::where('customer_phone', $this->contact)->select('order_id','customer_phone','order_date', 'subtotal')->orderBy('order_id', 'desc')->get(); // Fetch your data
    }
    else{
        $orders = ProductOrderDetails2::where('customer_phone', $this->contact)->select('order_id','customer_phone','order_date', 'subtotal')->orderBy('order_id', 'desc')->get(); // Fetch your data
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
    	$today = date('Y-m-d');
        if($this->storeUser == 1)
        {
            $orderQuery = ProductOrderDetails::where('customer_phone', $this->contact)->orderBy('id', 'desc');
             
        }
        else{
            $orderQuery = ProductOrderDetails2::where('customer_phone', $this->contact)->orderBy('id', 'desc');
        }

        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $orderQuery = $orderQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $orderQuery = $orderQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        $this->customer_details = $orderQuery->get();

        return view('livewire.admin.customer.customer-details');
    }
}
