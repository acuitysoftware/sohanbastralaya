<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderDetails extends Model
{
    use HasFactory;
    protected $table = 'st_product_order_details';
	protected $guarded = [];
	public $timestamps = false;

	public function productOrder()
	{
		return $this->belongsTo(ProductOrder::class, 'order_id', 'order_id');
	}

	public function productDetails()
	{
		return $this->hasMany(ProductOrder::class, 'order_id', 'order_id');
	}

	public function returnProducts()
	{
		return $this->hasMany(ReturnProduct::class, 'order_id', 'order_id');
	}

	public function returnProduct()
	{
		return $this->hasMany(ReturnProduct::class, 'order_id', 'order_id')->where('status', 'active');
	}

	public function productOrderDailyReport()
	{
		return $this->hasOne(ProductOrder::class, 'order_id', 'order_id');
	}

	public function card()
	{
		return $this->hasOne(Membership::class, 'order_id', 'order_id');
	}

	public function totalCardPoints()
	{
		return $this->hasMany(Membership::class, 'order_id', 'order_id')->sum('credit_points');
	}

	public function cards()
	{
		return $this->hasMany(Membership::class, 'order_id', 'order_id');
	}

	public function membershipsCards()
	{
		$today = date('Y-m-d');
		return $this->hasMany(Membership::class, 'contact', 'customer_phone')->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id');
	}

	public function membershipsCard()
	{
		$today = date('Y-m-d');
		return $this->hasOne(Membership::class, 'contact', 'customer_phone')->where('card_status', 'Y')->where('add_date','<=', $today)->where('expiry_date','>=', $today)->orderBy('id', 'desc');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'billing_user','id');
	}
}
