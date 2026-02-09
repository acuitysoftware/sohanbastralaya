<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $table = 'st_product_order';
	protected $fillable = [
        'order_id', 'billing_user', 'product_id', 'product_name', 'product_code','qty','selling_price', 'discount', 'purchase_price', 'subtotal', 
    ];
	public $timestamps = false;

	protected $appends = [
        'total_purchase_price', 'total_discount', 'profit', 'profit_percentage'
    ];

    public function getTotalPurchasePriceAttribute()
    {
        return $this->purchase_price * $this->qty;
    }

    public function getProfitAttribute()
    {
        return $this->subtotal-(($this->purchase_price * $this->qty)+($this->discount * $this->qty));
    }

    public function getProfitPercentageAttribute()
    {
        return round(((($this->selling_price-$this->discount)-$this->purchase_price)*100)/($this->selling_price-$this->discount),2);
    }

    public function getTotalDiscountAttribute()
    {
        return $this->discount * $this->qty;
    }

	public function customer()
	{
		return $this->hasOne(ProductOrderDetails::class, 'order_id', 'order_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'billing_user','id');
	}
	

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
	public function orderDetails()
	{
		return $this->hasOne(ProductOrderDetails::class, 'order_id', 'order_id');
	}
}
