<?php

namespace App\Http\Livewire\Admin\Order;

use DB;
use Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProductOrder;
use App\Models\ReturnProduct;
use App\Models\ProductOrderDetails;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class OrderAction extends Component
{
	public $order_id, $orderDetails;
	public function mount($id)
	{
		$this->setting = Setting::first();
		$this->order_id = $id;
	}

    public function render()
    {
        return view('livewire.admin.order.order-action');
    }
	public function viewOrder($orderId)
    {
        $this->orderDetails = ProductOrderDetails::with('productDetails','returnProducts')->find($orderId);

        $this->dispatchBrowserEvent('show-order-view-form');
    }
}
