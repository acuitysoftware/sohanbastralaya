<?php

namespace App\Http\Livewire\Admin\ProductReport;

use DB;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Gallery;
use App\Models\ProductQuantity;
use App\Models\UserPurchaseReport as ModelUserPurchaseReport;
use App\Models\ProductOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class UserPurchaseReport extends Component
{
    use AlertMessage;
    use WithPagination;
	public $perPage, $orderList=[],$product, $dateForm, $dateTo, $note, $date, $purchase_price, $total_price,$edit_note, $edit_date, $edit_purchase_price, $report_id;
    protected $paginationTheme = 'bootstrap';
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers','loadMore'];

	public function mount()
	{
		$this->perPage =env('PER_PAGE', 50); 
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

    public function save()
    {
    	$data = $this->validate([
    		'note' => 'required',
    		'date' => 'required',
    		'purchase_price' => 'required|numeric',
    	]);
    	ModelUserPurchaseReport::create($data);
        return redirect()->route('user_purchase_report');
    }

    public function render()
    {
    	$purchaseReportQuery = ModelUserPurchaseReport::query();
    	if ($this->dateForm && $this->dateTo)
    	{
    		$date['form_date'] = $this->dateForm;
    		$date['to_date'] = $this->dateTo;
           $purchaseReportQuery = $purchaseReportQuery->whereBetween('date',[$date['form_date'],$date['to_date']]);
    	}
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $purchaseReportQuery = $purchaseReportQuery->where(DB::raw("DATE(date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $purchaseReportQuery = $purchaseReportQuery->where(DB::raw("DATE(date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        return view('livewire.admin.product-report.user-purchase-report', [
            'reports' => $purchaseReportQuery->orderBy('date', 'desc')->paginate($this->perPage)
        ]);
    }

    public function edit($id)
    {
    	$data = ModelUserPurchaseReport::find($id);
    	$this->report_id = $data->id;
    	$this->edit_note = $data->note;
    	$this->edit_date = $data->date;
    	$this->edit_purchase_price = $data->purchase_price;
    	$this->dispatchBrowserEvent('show-edit-purchase-report');

    }
    public function updateReport()
    {
    	$data = $this->validate([
    		'edit_note' => 'required',
    		'edit_date' => 'required',
    		'edit_purchase_price' => 'required|numeric',
    	]);
    	$report = ModelUserPurchaseReport::find($this->report_id);
    	$report->update([
    		'note' => $this->edit_note,
    		'date' => $this->edit_date,
    		'purchase_price' => $this->edit_purchase_price,
    	]);
        return redirect()->route('user_purchase_report')->with('success', 'Data update successfully');
    }
    public function deleteAttempt($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "You won't be able to recover this Order!", 'Yes, delete!', 'deleteConfirm', ['id' => $id]);
    }

    public function deleteConfirm($id)
    {
        $deleteOrder = ModelUserPurchaseReport::find($id['id']);
        
        $deleteOrder->delete();
        $this->showModal('success', 'Success', 'Data been deleted successfully');
    }
    
}
