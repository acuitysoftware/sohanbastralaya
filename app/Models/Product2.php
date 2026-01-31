<?php

namespace App\Models;
use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'st_product';
	protected $guarded = [];
	public $timestamps = false;

	
	public function setNameAttribute($name)
    {
        $this->attributes['name'] = Str::ucfirst($name);
    }
    /* protected $appends = [
        'total_purchase_price', 'total_selling_price',
    ];

    public function getTotalPurchasePriceAttribute()
    {
        return $this->purchase_price * $this->quantity;
    }
    public function getTotalSellingPriceAttribute()
    {
        return $this->selling_price * $this->quantity;
    }  */

	public function gallery()
	{
		return $this->hasOne(Gallery2::class, 'product_id');
	}

	public function productQuantities()
	{
		return $this->hasMany(ProductQuantity2::class, 'product_id');
	}

	public function productOrders()
	{
		return $this->hasMany(ProductOrder2::class, 'product_id');
	}
	public function productOrdersByDesc()
	{
		return $this->hasMany(ProductOrder2::class, 'product_id')->orderBy('id', 'desc');
	}

	public function cart()
	{
		return $this->hasOne(CartItem2::class);
	}

	public function returnProducts()
	{
		return $this->hasMany(ReturnProduct2::class, 'product_id');
	}

	public function returnProductsQuantity()
	{
		return $this->hasMany(ReturnProduct2::class,'product_id');
		/* return $this->hasMany(ReturnProduct2::class,'product_id')->where('status','active'); */
	}

	public function productReductions()
	{
		return $this->hasMany(EditProductStock2::class, 'product_id')->where('qty','>', 0);
	}
}
