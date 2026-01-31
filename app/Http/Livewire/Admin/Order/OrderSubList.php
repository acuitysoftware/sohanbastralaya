<?php

namespace App\Http\Livewire\Admin\Order;

use Hash;
use Validator;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class OrderSubList extends Component
{
	use WithPagination, WithFileUploads;
    use WithSorting;
    use AlertMessage;
    public $total_selling, $total_purchase_price, $total_discount, $discount_percentage, $profit_percentage, $total_purchase, $total_profit;
    protected $paginationTheme = 'bootstrap';
	public function mount()
    {
        
        $this->total_purchase = ProductOrder::get();
        $this->total_selling = $this->total_purchase->sum('subtotal');
        $this->total_purchase_price = $this->total_purchase->sum('total_purchase_price');
        $this->total_discount = $this->total_purchase->sum('total_discount');
        $this->total_profit = ($this->total_selling-($this->total_purchase_price+$this->total_discount));
        if($this->total_discount)
        {
            $this->discount_percentage = round((($this->total_discount*100 )/$this->total_selling),2);
        }
        $this->profit_percentage = round((($this->total_profit*100 )/$this->total_selling),2);  

    }
    
    public function render()
    {
        return view('livewire.admin.order.order-sub-list');
    }
}
