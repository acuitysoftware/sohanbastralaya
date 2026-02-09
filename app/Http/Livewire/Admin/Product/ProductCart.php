<?php

namespace App\Http\Livewire\Admin\Product;


use Auth;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Product2;
use App\Models\CartItem;
use App\Models\CartItem2;
use App\Models\OrderDueAmount;
use App\Models\OrderDueAmount2;
use App\Models\Membership;
use App\Models\Membership2;
use App\Models\Setting;
use App\Models\Gallery;
use App\Models\Gallery2;
use App\Models\ProductQuantity;
use App\Models\ProductQuantity2;
use App\Models\ProductOrder;
use App\Models\ProductOrder2;
use App\Models\ProductOrderDetails;
use App\Models\ProductOrderDetails2;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Livewire\Traits\WithSorting;
use App\Http\Livewire\Traits\AlertMessage;

class ProductCart extends Component
{
    use WithSorting;
    use AlertMessage;
    public $cartItems, $total_amount = 0.00, $sub_total = 0.00, $cart_quantity = [];
    public $discount_type = 1, $total_cart_discount = 0.00, $cart_percentage = 0.00, $Percentage_amount, $discount = [], $user_type;
    public $customer_names = [], $customer_phones = [], $customer_emails = [], $returnProducts = [];
    public $customer_name, $customer_phone, $customer_email, $customer_address = '', $viewOrder, $card_number, $credit_points, $setting, $wallet = 0, $debit_points = 0, $available_points, $return = 0, $return_order_id = '', $return_discount, $count_cart_item, $storeUser, $due_amount, $collected_amount, $error_due_amount;

    public function mount()
    {
        $this->setting = Setting::first();
        if (Auth::user()->type == 'A') {
            $this->storeUser = 1;
            $store = Session::get('store');
            if ($store) {
                $this->storeUser = $store;
            }
        } else {

            $this->storeUser = Auth::user()->store;
        }
        if ($this->storeUser == 1) {
            $this->returnProducts = ProductOrderDetails::withCount('returnProduct')->with('returnProduct')->having('return_product_count', '>', 0)->orderBy('id', 'desc')->get();
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->count_cart_item = CartItem::where('user_id', Auth::user()->id)->count();
        } else {
            $this->returnProducts = ProductOrderDetails2::withCount('returnProduct')->with('returnProduct')->having('return_product_count', '>', 0)->orderBy('id', 'desc')->get();
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->count_cart_item = CartItem2::where('user_id', Auth::user()->id)->count();
        }


        if (count($this->cartItems)) {
            foreach ($this->cartItems as $key => $value) {
                $this->discount[$key] = $value->discount;
                $this->cart_quantity[$key] = $value->quantity;
                $this->total_amount += (($value->selling_price * $value->quantity) - (floatval($value->total_discount)));
                $this->sub_total += ($value->selling_price * $value->quantity);
                $this->total_cart_discount += floatval($value->total_discount);
            }
        }
        if ($this->total_cart_discount) {
            $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        }
    }

    public function updatedWallet($data)
    {
        if ($data) {
            $this->wallet = 1;
        } else {
            $this->wallet = 0;
        }
    }
    public function updatedDueAmount($data)
    {
        $this->error_due_amount = null;
        if ($data) {
            $this->total_amount = $this->total_amount;
            if ((float)$data > (float)$this->total_amount) {
                $this->error_due_amount = "Amount should be less than or equal total amount";
                $this->collected_amount = 0.00;
                return false;
            }
        } else {
            $this->collected_amount = null;
        }
    }

    public function updatedReturn($data)
    {
        if ($data) {
            $this->return = 1;
        } else {
            $this->return = 0;
        }
    }

    public function updatedReturnOrderId($data)
    {
        if ($data != "") {
            if ($this->storeUser == 1) {
                $order = ProductOrderDetails::with('returnProduct')->where('order_id', $data)->first();

                $total_return_amount = $order->returnProduct()->sum('price');
                $total_discount = 0;
                if (count($order->returnProduct)) {
                    foreach ($order->returnProduct as $key => $value) {
                        $total_discount += ($value->discount * $value->qty);
                    }
                }
                $return_amount = ($total_return_amount - $total_discount);
                if ($return_amount > $this->total_amount) {
                    $this->showModal('error', 'Error', 'Add more item in your cart to get this return amount');
                } else {
                    $this->return_discount = (floatval($return_amount));

                    $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $this->sub_total = 0.00;
                    $this->total_cart_discount = 0.00;
                    foreach ($this->cartItems as $key1 => $item) {
                        $this->sub_total += ($item->selling_price * $item->quantity);
                        $this->total_cart_discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($this->sub_total - $this->total_cart_discount);
                    if ($this->debit_points) {
                        $this->total_amount = ($this->total_amount - $this->debit_points);
                    }
                    $this->total_amount = ($this->total_amount - floatval($this->return_discount));
                }
            } else {
                $order = ProductOrderDetails2::with('returnProduct')->where('order_id', $data)->first();
                $total_return_amount = $order->returnProduct()->sum('price');
                $total_discount = 0;
                if (count($order->returnProduct)) {
                    foreach ($order->returnProduct as $key => $value) {
                        $total_discount += ($value->discount * $value->qty);
                    }
                }
                $return_amount = ($total_return_amount - $total_discount);
                if ($return_amount > $this->total_amount) {
                    $this->showModal('error', 'Error', 'Add more item in your cart to get this return amount');
                } else {
                    $this->return_discount = (floatval($return_amount));

                    $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $this->sub_total = 0.00;
                    $this->total_cart_discount = 0.00;
                    foreach ($this->cartItems as $key1 => $item) {
                        $this->sub_total += ($item->selling_price * $item->quantity);
                        $this->total_cart_discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($this->sub_total - $this->total_cart_discount);
                    if ($this->debit_points) {
                        $this->total_amount = ($this->total_amount - $this->debit_points);
                    }
                    $this->total_amount = ($this->total_amount - floatval($this->return_discount));
                }
            }
        }
    }

    public function updatedDebitPoints($data)
    {
        if ($this->storeUser == 1) {
            if ($data != "") {
                if ($this->credit_points >= floatval($data)) {
                    $this->available_points = $this->credit_points - floatval($data);
                    $this->cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $total = 0;
                    $discount = 0;
                    foreach ($this->cartItems as $key1 => $item) {
                        $total += ($item->selling_price * $item->quantity);
                        $discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($total - $discount);
                    $this->total_amount = $this->total_amount - floatval($data);
                    $this->return_order_id = '';
                    $this->return_discount = '';
                } else {
                    $this->debit_points = 0;
                    $this->available_points = $this->credit_points;
                    $this->cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $total = 0;
                    $discount = 0;
                    foreach ($this->cartItems as $key1 => $item) {
                        $total += ($item->selling_price * $item->quantity);
                        $discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($total - $discount);
                    $msg = "You have to use wallet within " . $this->credit_points . " points";
                    $this->showModal('error', 'Error', $msg);
                }
            } else {
            }
        } else {
            if ($data != "") {
                if ($this->credit_points >= floatval($data)) {
                    $this->available_points = $this->credit_points - floatval($data);
                    $this->cartItems = CartItem2::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $total = 0;
                    $discount = 0;
                    foreach ($this->cartItems as $key1 => $item) {
                        $total += ($item->selling_price * $item->quantity);
                        $discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($total - $discount);
                    $this->total_amount = $this->total_amount - floatval($data);
                    $this->return_order_id = '';
                    $this->return_discount = '';
                } else {
                    $this->debit_points = 0;
                    $this->available_points = $this->credit_points;
                    $this->cartItems = CartItem2::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                    $total = 0;
                    $discount = 0;
                    foreach ($this->cartItems as $key1 => $item) {
                        $total += ($item->selling_price * $item->quantity);
                        $discount += floatval($item->total_discount);
                    }
                    $this->total_amount = ($total - $discount);
                    $msg = "You have to use wallet within " . $this->credit_points . " points";
                    $this->showModal('error', 'Error', $msg);
                }
            } else {
            }
        }
    }

    public function updatedCustomerPhone($data)
    {
        if ($data != "") {
            if ($this->storeUser == 1) {
                $this->customer_phones = ProductOrderDetails::select('id', 'customer_phone')->where('customer_phone', 'like', $data . '%')->groupBy('customer_phone')->get();
            } else {
                $this->customer_phones = ProductOrderDetails2::select('id', 'customer_phone')->where('customer_phone', 'like', $data . '%')->groupBy('customer_phone')->get();
            }
            if (count($this->customer_phones) == 0) {
                $this->card_number = null;
                $this->credit_points = null;
            }
        } else {
            $this->customer_phones = [];
        }
    }

    public function getCustomer($data)
    {
        $today = date('Y-m-d');
        if ($this->storeUser == 1) {
            $customer = ProductOrderDetails::where('customer_phone', $data)->orderBy('id', 'desc')->first();
            if ($customer) {
                $total_debit_point = 0;
                $membership_card = Membership::where('contact', $data)->where('card_status', 'Y')->orderBy('id', 'desc')->first();
                /* $membership_card = Membership::where('contact',$data)->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc')->first(); */
                $credit_points = Membership::where('contact', $data)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->sum('credit_points');
                $total_debit_point = Membership::where('contact', $data)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->sum('debit_point');
                $this->customer_names = [];
                $this->customer_emails = [];
                $this->customer_phones = [];
                $this->customer_name = $customer->customer_name;
                $this->customer_email = $customer->customer_email;
                $this->customer_phone = $customer->customer_phone;
                $this->customer_address = $customer->customer_address;
                if ($membership_card) {
                    $this->card_number = $membership_card->card_number;
                    $this->credit_points = $credit_points - $total_debit_point;
                    $this->available_points = $credit_points - $total_debit_point;
                }
            } else {
                $this->card_number = 0;
                $this->credit_points = 0;
            }
        } else {
            $customer = ProductOrderDetails2::where('customer_phone', $data)->orderBy('id', 'desc')->first();
            if ($customer) {
                $total_debit_point = 0;
                $membership_card = Membership2::where('contact', $data)->where('card_status', 'Y')->orderBy('id', 'desc')->first();
                $credit_points = Membership2::where('contact', $data)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->sum('credit_points');
                $total_debit_point = Membership2::where('contact', $data)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->sum('debit_point');
                $this->customer_names = [];
                $this->customer_emails = [];
                $this->customer_phones = [];
                $this->customer_name = $customer->customer_name;
                $this->customer_email = $customer->customer_email;
                $this->customer_phone = $customer->customer_phone;
                $this->customer_address = $customer->customer_address;
                if ($membership_card) {
                    $this->card_number = $membership_card->card_number;
                    $this->credit_points = $credit_points - $total_debit_point;
                    $this->available_points = $credit_points - $total_debit_point;
                }
            } else {
                $this->card_number = 0;
                $this->credit_points = 0;
            }
        }
    }

    public function discount_type($data)
    {
        $this->discount_type = $data;
    }

    public function updatedCartPercentage($data)
    {
        $this->sub_total = 0.00;
        $this->total_cart_discount = 0.00;
        $this->cart_percentage = 0.00;

        if ($this->storeUser == 1)
            $this->cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        if (count($this->cartItems) > 0) {
            foreach ($this->cartItems as $key => $value) {
                $discount = 0;
                $total_discount = 0;
                $discount += ((floatval($data) * $value->selling_price) / 100);
                $total_discount += ($discount * $value->quantity);
                $value->update([
                    'discount' => $discount,
                    'total_discount' => $total_discount,
                ]);
            }
        }
        if ($this->storeUser == 1)
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        foreach ($this->cartItems as $key1 => $item) {
            $this->discount[$key1] = $item->discount;
            $this->cart_quantity[$key1] = $item->quantity;
            $this->sub_total += ($item->selling_price * $item->quantity);
            $this->total_cart_discount += floatval($item->total_discount);
        }
        $this->total_amount = ($this->sub_total - $this->total_cart_discount);

        $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        $this->return_order_id = '';
        $this->return_discount = '';
    }

    public function updatedTotalCartDiscount($data)
    {

        $this->sub_total = 0.00;
        $this->total_cart_discount = 0.00;
        $this->cart_percentage = 0.00;

        if ($this->storeUser == 1)
            $this->cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        if (count($this->cartItems) > 0) {
            foreach ($this->cartItems as $key => $value) {
                $discount = 0;
                $total_discount = 0;
                if (isset($data) && $data != "" && is_numeric($data)) {
                    $total_discount = floatval($data) * ($this->cartItems[$key]->quantity);
                    $this->cartItems[$key]->update([
                        'discount' => $data,
                        'total_discount' => $total_discount,
                    ]);
                }
            }
        }
        if ($this->storeUser == 1)
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        foreach ($this->cartItems as $key1 => $item) {
            $this->discount[$key1] = $item->discount;
            $this->cart_quantity[$key1] = $item->quantity;
            $this->sub_total += ($item->selling_price * $item->quantity);
            $this->total_cart_discount += floatval($item->total_discount);
        }
        $this->total_amount = ($this->sub_total - $this->total_cart_discount);

        $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        $this->return_order_id = '';
        $this->return_discount = '';
    }

    public function updatedDiscount()
    {
        $this->sub_total = 0.00;
        $this->total_cart_discount = 0.00;
        $this->cart_percentage = 0.00;
        $total_discount = 0;

        if ($this->storeUser == 1)
            $this->cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        foreach ($this->discount as $key => $value) {
            $this->total_amount = 0.00;
            if (isset($value) && $value != "" && is_numeric($value)) {
                $total_discount = floatval($value) * ($this->cartItems[$key]->quantity);
                $this->cartItems[$key]->update([
                    'discount' => $value,
                    'total_discount' => $total_discount,
                    ]);
            }
            else{
                $this->cartItems[$key]->update([
                    'discount' => null,
                    'total_discount' => null,
                ]);
            }
        }
        if ($this->storeUser == 1)
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        else
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        foreach ($this->cartItems as $key1 => $item) {
            $this->discount[$key1] = $item->discount;
            $this->cart_quantity[$key1] = $item->quantity;
            $this->total_amount += (($item->selling_price * $item->quantity) - (floatval($item->total_discount)));
            $this->sub_total += ($item->selling_price * $item->quantity);
            $this->total_cart_discount += floatval($item->total_discount);
        }
        $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        $this->return_order_id = '';
        $this->return_discount = '';
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    protected $listeners = ['deleteConfirm', 'changeStatus'];

    public function render()
    {
        if ($this->storeUser == 1) {
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            foreach ($this->cartItems as $key1 => $item) {
                $this->sub_total += ($item->selling_price * $item->quantity);
                $this->total_cart_discount += floatval($item->total_discount);
            }
            $this->total_amount = ($this->sub_total - $this->total_cart_discount);
            if ($this->debit_points) {
                $this->total_amount = ($this->total_amount - $this->debit_points);
            }
            $this->total_amount = ($this->total_amount - floatval($this->return_discount));
        } else {
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            foreach ($this->cartItems as $key1 => $item) {
                $this->sub_total += ($item->selling_price * $item->quantity);
                $this->total_cart_discount += floatval($item->total_discount);
            }
            $this->total_amount = ($this->sub_total - $this->total_cart_discount);
            if ($this->debit_points) {
                $this->total_amount = ($this->total_amount - $this->debit_points);
            }
            $this->total_amount = ($this->total_amount - floatval($this->return_discount));
        }
        $this->collected_amount = ((float)$this->total_amount - (float)$this->due_amount);
        if ($this->error_due_amount) {
            $this->collected_amount = 0.00;
        }
        return view('livewire.admin.product.product-cart');
    }

    public function deleteAttempt($id)
    {
        $this->showConfirmation("warning", 'Are you sure?', "Remove from cart!", 'Yes, delete!', 'deleteConfirm', ['id' => $id]);
    }

    public function deleteConfirm($id)
    {
        if ($this->storeUser == 1) {
            CartItem::destroy($id);
            $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->total_amount = 0.00;
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            $this->cart_percentage = 0.00;
            $this->discount = [];
            if (count($this->cartItems) > 0) {
                foreach ($this->cartItems as $key => $value) {
                    $this->discount[$key] = $value->discount;
                    $this->cart_quantity[$key] = $value->quantity;
                    $this->total_amount += (($value->selling_price * $value->quantity) - (floatval($value->total_discount)));
                    $this->sub_total += ($value->selling_price * $value->quantity);
                    $this->total_cart_discount += floatval($value->total_discount);
                }
                $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
            }
            $this->count_cart_item = CartItem::where('user_id', Auth::user()->id)->count();
            $this->showModal('success', 'Success', 'Item has been deleted successfully');
        } else {
            CartItem2::destroy($id);
            $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $this->total_amount = 0.00;
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            $this->cart_percentage = 0.00;
            $this->discount = [];
            if (count($this->cartItems) > 0) {
                foreach ($this->cartItems as $key => $value) {
                    $this->discount[$key] = $value->discount;
                    $this->cart_quantity[$key] = $value->quantity;
                    $this->total_amount += (($value->selling_price * $value->quantity) - (floatval($value->total_discount)));
                    $this->sub_total += ($value->selling_price * $value->quantity);
                    $this->total_cart_discount += floatval($value->total_discount);
                }
                $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
            }
            $this->count_cart_item = CartItem2::where('user_id', Auth::user()->id)->count();
            $this->showModal('success', 'Success', 'Item has been deleted successfully');
        }
    }

    public function decrementQuantity($id, $value)
    {
        if ($this->storeUser == 1)
            $changeProduct = CartItem::find($id);
        else
            $changeProduct = CartItem2::find($id);

        if ($this->cart_quantity[$value] == '1') {
            $this->showModal('error', 'Error', 'Quantity should be greater equal to 1');
        } else {
            $this->total_amount = 0.00;
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            $this->cart_percentage = 0.00;
            $total_discount = 0;
            if (isset($changeProduct->discount)) {
                $total_discount = floatval($changeProduct->discount) * ($this->cart_quantity[$value] - 1);
            }
            $changeProduct->update([
                'quantity' => $this->cart_quantity[$value] - 1,
                'total_discount' => $total_discount,
            ]);
            $this->cart_quantity[$value] = $this->cart_quantity[$value] - 1;

            if ($this->storeUser == 1)
                $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            else
                $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

            foreach ($this->cartItems as $key => $item) {
                $this->cart_quantity[$key] = $item->quantity;
                $this->total_amount += (($item->selling_price * $item->quantity) - (floatval($item->total_discount)));
                $this->sub_total += ($item->selling_price * $item->quantity);
                $this->total_cart_discount += floatval($item->total_discount);
            }
            $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        }
    }

    public function incrementQuantity($id, $value)
    {
        if ($this->storeUser == 1) {
            $changeProduct = CartItem::find($id);
            $product = Product::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($changeProduct->product_id);
        } else {
            $changeProduct = CartItem2::find($id);
            $product = Product2::withSum('productQuantities', 'quantity')->withSum('productOrders', 'qty')->withSum('returnProductsQuantity', 'qty')->find($changeProduct->product_id);
        }


        $changeProductQuantity = $product->quantity;
        if ($changeProductQuantity <= $this->cart_quantity[$value]) {
            $this->showModal('error', 'Error', 'Quantity should be less equal to product quantity');
        } else {
            $this->total_amount = 0.00;
            $this->sub_total = 0.00;
            $this->total_cart_discount = 0.00;
            $this->cart_percentage = 0.00;
            $discount_amount = 0;
            $total_discount = 0;
            if (isset($changeProduct->discount)) {
                $total_discount = floatval($changeProduct->discount) * ($this->cart_quantity[$value] + 1);
            }
            $changeProduct->update([
                'quantity' => $this->cart_quantity[$value] + 1,
                'total_discount' => $total_discount,
            ]);

            if ($this->storeUser == 1)
                $this->cartItems = CartItem::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            else
                $this->cartItems = CartItem2::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

            foreach ($this->cartItems as $key => $item) {
                $this->cart_quantity[$key] = $item->quantity;
                $this->total_amount += (($item->selling_price * $item->quantity) - (floatval($item->total_discount)));
                $this->sub_total += ($item->selling_price * $item->quantity);
                $this->total_cart_discount += floatval($item->total_discount);
            }
            $this->cart_percentage = round((($this->total_cart_discount / $this->sub_total) * 100), 2);
        }
    }

    public function orderPlaced()
    {
        if (count($this->cartItems) > 0) {
            if ($this->storeUser == 1) {
                $wallet_amt = 0;
                $wallet_discount = '';
                $return_amount = '';
                if ($this->wallet == 1) {
                    $wallet_amt = $this->credit_points - $this->debit_points;
                    $wallet_discount = $this->debit_points;
                }
                if ($this->return_order_id != '') {
                    $order = ProductOrderDetails::with('returnProduct')->where('order_id', $this->return_order_id)->first();
                    $order->returnProduct()->update(['status' => 'inactive']);
                }


                $orderData = ProductOrderDetails::create([
                    'customer_name'  => $this->customer_name ? $this->customer_name : '',
                    'customer_phone'  => $this->customer_phone ? $this->customer_phone : '',
                    'customer_email'  => $this->customer_email,
                    'customer_address'  => $this->customer_address,
                    'card_number'  => $this->card_number ? $this->card_number : '',
                    /*'subtotal'  =>$this->return_discount?$this->sub_total-$this->return_discount:$this->sub_total,*/
                    'subtotal'  => $this->sub_total,
                    'billing_user'  => Auth::user()->id,
                    'discount_type'  => $this->discount_type == 1 ? 'dis_amt' : 'dis_per',
                    'discount_percent'  => $this->discount_type == 0 ? $this->cart_percentage : 0,
                    'perctge_amt'  => $this->discount_type == 0 ? $this->total_cart_discount : 0.00,
                    'discount_amt'  => $this->discount_type == 1 ? $this->total_cart_discount : 0.00,
                    'total_amount'  => $this->total_amount,
                    'return_id'  => $this->return_order_id,
                    'return_amt'  => $this->return_discount ? $this->return_discount : '',
                    'wallet'  => $this->wallet,
                    'due_amount'  => $this->due_amount??0,
                    'collected_amount'  => $this->collected_amount ?? $this->total_amount,
                    'wallet_amt'  => $wallet_amt,
                    'wallet_discount'  => $wallet_discount,
                    'total_points'  => 0,
                    'order_date'  => date('Y-m-d'),
                    'order_time'  => date('Y-m-d H:i'),
                ]);
                $order_id = '0' . $orderData->id;

                $orderData->update(['order_id' => $order_id]);

                foreach ($this->cartItems as $key => $item) {
                    ProductOrder::create([
                        'order_id' => $order_id,
                        'billing_user'  => Auth::user()->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_code' => $item->product->product_code,
                        'qty' => $item->quantity,
                        'selling_price' => $item->selling_price,
                        'discount' => $item->discount,
                        'purchase_price' => $item->product->purchase_price,
                        'subtotal' => ($item->selling_price * $item->quantity),
                    ]);
                    $item->product()->update(['quantity' => $item->product->quantity - $item->quantity]);
                    $item->delete();
                }
                $expiry_date = null;
                $today = date('Y-m-d');
                $old_membership = Membership::where('contact', $this->customer_phone)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->orderBy('id', 'asc')->first();
                $credit_point = 0;
                /* if($this->card_number)
                {
                   $credit_point = (($this->setting->target_points*$this->total_amount)/100);
                   if($old_membership)
                   {
                        $expiry_date = $old_membership->expiry_date;
                   }else
                   {
                        $expiry_date = date('Y-m-d',strtotime($today . " + ". $this->setting->expire_days ." day"));
                   }
                } */

                $credit_point = (($this->setting->target_points * $this->total_amount) / 100);
                $expiry_date = date('Y-m-d', strtotime($today . " + " . $this->setting->expire_days . " day"));


                Membership::create([
                    'order_id' => $order_id,
                    'card_number'  => $this->card_number ? $this->card_number : 0,
                    'card_status'  => $this->card_number ? 'Y' : 'N',
                    'contact'  => $this->customer_phone ? $this->customer_phone : 0,
                    'card_name'  => $this->customer_name ? $this->customer_name : '',
                    'credit_points'  => $credit_point,
                    'grand_amt'  => $this->total_amount,
                    'add_date'  => date('Y-m-d'),
                    'sub_date'  => date('Y-m-d'),
                    'debit_point'  => $this->debit_points,
                    'expiry_date'  => $expiry_date,
                ]);
                if ($this->due_amount >0) {
                    OrderDueAmount::create([
                        'order_id' => $order_id,
                        'total_amount'  => $this->total_amount,
                        'due_amount'  => $this->due_amount,
                        'collected_amount'  => $this->collected_amount,
                        'order_date'  => date('Y-m-d'),
                        'date'  => date('Y-m-d'),
                    ]);
                }

                $this->viewOrder = ProductOrderDetails::with('productDetails')->where('order_id', $order_id)->first();
                $this->dispatchBrowserEvent('show-product-order-form');
            } else {
                $wallet_amt = 0;
                $wallet_discount = '';
                $return_amount = '';
                if ($this->wallet == 1) {
                    $wallet_amt = $this->credit_points - $this->debit_points;
                    $wallet_discount = $this->debit_points;
                }
                if ($this->return_order_id != '') {
                    $order = ProductOrderDetails2::with('returnProduct')->where('order_id', $this->return_order_id)->first();
                    $order->returnProduct()->update(['status' => 'inactive']);
                }

                $orderData = ProductOrderDetails2::create([
                    'customer_name'  => $this->customer_name ? $this->customer_name : '',
                    'customer_phone'  => $this->customer_phone ? $this->customer_phone : '',
                    'customer_email'  => $this->customer_email,
                    'customer_address'  => $this->customer_address,
                    'card_number'  => $this->card_number ? $this->card_number : '',
                    /*'subtotal'  =>$this->return_discount?$this->sub_total-$this->return_discount:$this->sub_total,*/
                    'subtotal'  => $this->sub_total,
                    'billing_user'  => Auth::user()->id,
                    'discount_type'  => $this->discount_type == 1 ? 'dis_amt' : 'dis_per',
                    'discount_percent'  => $this->discount_type == 0 ? $this->cart_percentage : 0,
                    'perctge_amt'  => $this->discount_type == 0 ? $this->total_cart_discount : 0.00,
                    'discount_amt'  => $this->discount_type == 1 ? $this->total_cart_discount : 0.00,
                    'total_amount'  => $this->total_amount,
                    'return_id'  => $this->return_order_id,
                    'return_amt'  => $this->return_discount ? $this->return_discount : '',
                    'wallet'  => $this->wallet,
                    'due_amount'  => $this->due_amount??0,
                    'collected_amount'  => $this->collected_amount ?? $this->total_amount,
                    'wallet_amt'  => $wallet_amt,
                    'wallet_discount'  => $wallet_discount,
                    'total_points'  => 0,
                    'order_date'  => date('Y-m-d'),
                    'order_time'  => date('Y-m-d H:i'),
                ]);
                $order_id = '0' . $orderData->id;

                $orderData->update(['order_id' => $order_id]);

                foreach ($this->cartItems as $key => $item) {
                    ProductOrder2::create([
                        'order_id' => $order_id,
                        'billing_user'  => Auth::user()->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_code' => $item->product->product_code,
                        'qty' => $item->quantity,
                        'selling_price' => $item->selling_price,
                        'discount' => $item->discount,
                        'purchase_price' => $item->product->purchase_price,
                        'subtotal' => ($item->selling_price * $item->quantity),
                    ]);
                    $item->product()->update(['quantity' => $item->product->quantity - $item->quantity]);
                    $item->delete();
                }
                $expiry_date = null;
                $today = date('Y-m-d');
                $old_membership = Membership2::where('contact', $this->customer_phone)->where('card_status', 'Y')->where('add_date', '<=', $today)->where('expiry_date', '>=', $today)->orderBy('id', 'asc')->first();
                $credit_point = 0;
                /* if($this->card_number)
                {
                   $credit_point = (($this->setting->target_points*$this->total_amount)/100);
                   if($old_membership)
                   {
                        $expiry_date = $old_membership->expiry_date;
                   }else
                   {
                        $expiry_date = date('Y-m-d',strtotime($today . " + ". $this->setting->expire_days ." day"));
                   }
                } */

                $credit_point = (($this->setting->target_points * $this->total_amount) / 100);
                $expiry_date = date('Y-m-d', strtotime($today . " + " . $this->setting->expire_days . " day"));

                Membership2::create([
                    'order_id' => $order_id,
                    'card_number'  => $this->card_number ? $this->card_number : 0,
                    'card_status'  => $this->card_number ? 'Y' : 'N',
                    'contact'  => $this->customer_phone ? $this->customer_phone : 0,
                    'card_name'  => $this->customer_name ? $this->customer_name : '',
                    'credit_points'  => $credit_point,
                    'grand_amt'  => $this->total_amount,
                    'add_date'  => date('Y-m-d'),
                    'sub_date'  => date('Y-m-d'),
                    'debit_point'  => $this->debit_points,
                    'expiry_date'  => $expiry_date,
                ]);
                 if ($this->due_amount >0) {
                OrderDueAmount2::create([
                    'order_id' => $order_id,
                    'total_amount'  => $this->total_amount,
                    'due_amount'  => $this->due_amount,
                    'collected_amount'  => $this->collected_amount,
                    'order_date'  => date('Y-m-d'),
                    'date'  => date('Y-m-d'),
                ]);
                 }

                $this->viewOrder = ProductOrderDetails2::with('productDetails')->where('order_id', $order_id)->first();
                $this->dispatchBrowserEvent('show-product-order-form');
            }
        } else {

            $this->showModal('error', 'Error', 'No products available');
        }
    }
}
