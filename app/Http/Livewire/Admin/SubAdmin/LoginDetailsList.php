<?php

namespace App\Http\Livewire\Admin\SubAdmin;

use Validator;
use Hash;
use DB;
use App\Http\Livewire\Traits\AlertMessage;
use App\Models\LoginDetails;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithSorting;

class LoginDetailsList extends Component
{
	use WithPagination;
    use WithSorting;
    use AlertMessage;
    public   $dateForm, $dateTo, $searchName;
    protected $paginationTheme = 'bootstrap';
	protected $listeners = ['deleteConfirm', 'changeStatus','deleteConfirmUsers'];
	public function mount()
    {

        $this->dateForm =date('Y-m-d');
        $this->dateTo =date('Y-m-d');
    }
    public function getRandomColor()
    {
        $arrIndex = array_rand($this->badgeColors);
        return $this->badgeColors[$arrIndex];
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

    public function render()
    {
        $loginQuery = LoginDetails::with('user');

        if ($this->searchName)
        {
           $loginQuery = $loginQuery->whereRelation('user' ,'name', 'like', '%' . $this->searchName . '%');
        }

        if ($this->dateForm && $this->dateTo)
    	{
    		$date['form_date'] = $this->dateForm;
    		$date['to_date'] = $this->dateTo;
           $loginQuery = $loginQuery->whereBetween('date',[$date['form_date'],$date['to_date']]);
    	}
        if($this->dateForm)
        {
            $date['form_date'] = $this->dateForm;
            $loginQuery = $loginQuery->where(DB::raw("DATE(date)"),'>=',date('Y-m-d',strtotime($date['form_date'])));
        }
        if($this->dateTo)
        {
            $date['to_date'] = $this->dateTo;
            $loginQuery = $loginQuery->where(DB::raw("DATE(date)"),'<=',date('Y-m-d',strtotime($date['to_date'])));
        }
        return view('livewire.admin.sub-admin.login-details-list', [
            'logins' => $loginQuery
                ->orderBy($this->sortBy, $this->sortDirection)
                ->get()
        ]);
    }
    
}
