<?php

namespace App\Http\Livewire\Admin\Customer;

use DB;
use Hash;
use Auth;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\Membership;
use App\Models\Membership2;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class CustomerCardList extends Component
{

	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public  $state=[], $type='edit', $deleteIds=[];
    public $perPage,$searchName, $searchPhone, $searchCard, $memberships=[], $membership, $contact_no, $card_number, $contact,$total_credit_points, $expiry_date_count, $storeUser,$dateForm, $dateTo;
    protected $listeners = ['loadMore'];
    protected $paginationTheme = 'bootstrap';
	public function mount()
    {
        $this->perPage = env('PER_PAGE', '50');
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
        $this->perPage= $this->perPage+env('PER_PAGE', '50');
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
       $this->searchPhone = null;
       $this->searchCard = null;
       $this->dateForm = null;
       $this->dateTo = null;
    }
    public function viewmemberships($value)
    {
        $today = date('Y-m-d');
        if($this->storeUser == 1){
            $this->memberships = Membership::with('order')->where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc')->get();

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
        else
        {
            $this->memberships = Membership2::with('order')->where('contact',$value)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc')->get();

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

    public function save()
    {
        $this->validate([
            'contact_no'=>'required|integer|digits:10',
            'card_number'=>'required|integer|digits:16',
        ]);
        if($this->storeUser == 1){

            $checkData =  Membership::where('contact','!=', $this->contact_no)->where('card_number', $this->card_number)->first();
            if($checkData){
                 $this->showToastr("error",'This card no already exists',false);
                 return false;
            }
            $data = ProductOrderDetails::where('customer_phone',$this->contact)->orderBy('id', 'desc')->first();
            $data->update(['customer_phone' => $this->contact_no,'card_number' =>$this->card_number]);
            Membership::where(['contact' => $this->contact_no])->update(['contact' => $this->contact_no,'card_number' =>$this->card_number,'card_status' => 'Y']);
        }
        else{
             $checkData =  Membership2::where('contact','!=', $this->contact_no)->where('card_number', $this->card_number)->first();
            if($checkData){
                 $this->showToastr("error",'This card no already exists',false);
                 return false;
            }
            $data = ProductOrderDetails2::where('customer_phone',$this->contact)->orderBy('id', 'desc')->first();
            $data->update(['customer_phone' => $this->contact_no,'card_number' =>$this->card_number]);
            Membership2::where(['contact' => $this->contact_no])->update(['contact' => $this->contact_no,'card_number' =>$this->card_number,'card_status' => 'Y']);
        }

        $this->showToastr("success",'Update your information');
        return redirect()->route('customer_card_list');
    }

    public function editMemberships($value)
    {
        $today = date('Y-m-d');
        if($this->storeUser == 1)
            $data = ProductOrderDetails::where('customer_phone',$value)->orderBy('id', 'desc')->first();
        else
            $data = ProductOrderDetails2::where('customer_phone',$value)->orderBy('id', 'desc')->first();

        $this->card_number = $data->card_number;
        $this->contact_no = $data->customer_phone;
        $this->contact = $data->customer_phone;
        $this->dispatchBrowserEvent('show-membership-card-edit');
    }

    
    public function render()
    {
        if($this->storeUser == 1){

            $customerQuery = ProductOrderDetails::select('customer_name', 'customer_phone','order_date','card_number')->where('customer_phone', '!=', "0")->with('cardData');
        }
        else{

            $customerQuery = ProductOrderDetails2::select('customer_name', 'customer_phone','order_date','card_number')->where('customer_phone', '!=', "0")->with('cardData');
        }
    	if ($this->searchPhone)
    	{
           $customerQuery = $customerQuery->where('customer_phone', 'like', '%' . $this->searchPhone . '%');
    	}

        if ($this->searchCard)
        {
           $customerQuery = $customerQuery->where('card_number', 'like', '%' . $this->searchCard . '%');
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
       // dd($customerQuery->take(2)->get());
        
        return view('livewire.admin.customer.customer-card-list', [
            'customers' => $customerQuery
                ->groupBy('customer_phone')
                ->orderBy('order_date', 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
