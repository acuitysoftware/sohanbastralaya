<?php

namespace App\Http\Livewire\Admin\Order;

use DB;
use Auth;
use Hash;
use Validator;
use Session;
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

class DailyOrderReport extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[],$perPage;
    public $searchName, $startDate, $endDate, $storeUser;
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers', 'loadMore'];
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

    public function resetSearch()
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    public function updatedStartDate($value)
    {
        $this->startDate = $value;
    }

    public function updatedEndDate($value)
    {
        $this->endDate = $value;
    }
    
    public function render()
    {
        if($this->storeUser == 1){
            $total_selling_data = ProductOrderDetails::select('order_date', DB::raw('SUM(total_amount) as total_price'));
            $orderQuery = ProductOrderDetails::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')
        ->select('order_date',DB::raw('sum(st_product_order.subtotal) AS sub_total'),DB::raw('sum(st_product_order.qty*st_product_order.purchase_price) AS total_purchase_price'),DB::raw('sum(st_product_order.qty*st_product_order.discount) AS total_discount'));
        }else{
            $total_selling_data = ProductOrderDetails2::select('order_date', DB::raw('SUM(total_amount) as total_price'));
            $orderQuery = ProductOrderDetails2::join('st_product_order', 'st_product_order.order_id', '=', 'st_product_order_details.order_id')
        ->select('order_date',DB::raw('sum(st_product_order.subtotal) AS sub_total'),DB::raw('sum(st_product_order.qty*st_product_order.purchase_price) AS total_purchase_price'),DB::raw('sum(st_product_order.qty*st_product_order.discount) AS total_discount'));
        }
        

        	if($this->startDate)
            {
                $total_selling_data->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($this->startDate)));
                $orderQuery->where(DB::raw("DATE(order_date)"),'>=',date('Y-m-d',strtotime($this->startDate)));
            }
            if($this->endDate)
            {
                $total_selling_data->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($this->endDate)));
                $orderQuery->where(DB::raw("DATE(order_date)"),'<=',date('Y-m-d',strtotime($this->endDate)));
            }

            return view('livewire.admin.order.daily-order-report', [
            'orders' => $orderQuery->groupBy('order_date')
            ->orderBy('order_date', 'desc')->paginate($this->perPage), 'total_selling_data' => $total_selling_data->groupBy('order_date')
            ->orderBy('order_date', 'desc')->paginate($this->perPage)
        ]);
    }


    
}
