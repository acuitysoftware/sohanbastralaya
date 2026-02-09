<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	use HasFactory;
	public $timestamps = false;
	protected $table = 'st_product';
	protected $guarded = [];


	public function setNameAttribute($name)
	{
		$this->attributes['name'] = Str::ucfirst($name);
	}

	/* public function scopeSearchQuery($query, $search_keyword)
	{
		return $query->whereAny(['name', 'product_code', 'bar_code'], 'like',"%{$search_keyword}%");
	} */
	/*protected $appends = [
        'total_purchase_price', 'total_selling_price',
    ];

    public function getTotalPurchasePriceAttribute()
    {
        return $this->purchase_price * $this->quantity;
    }
    public function getTotalSellingPriceAttribute()
    {
        return $this->selling_price * $this->quantity;
    } */

	public function gallery()
	{
		return $this->hasOne(Gallery::class);
	}
	public function galleries()
	{
		return $this->hasMany(Gallery::class);
	}

	public function productQuantities()
	{
		return $this->hasMany(ProductQuantity::class);
	}

	public function productOrders()
	{
		return $this->hasMany(ProductOrder::class);
	}
	public function productOrdersByDesc()
	{
		return $this->hasMany(ProductOrder::class)->orderBy('id', 'desc');
	}

	public function cart()
	{
		return $this->hasOne(CartItem::class);
	}

	public function returnProducts()
	{
		return $this->hasMany(ReturnProduct::class);
	}

	public function returnProductsQuantity()
	{
		return $this->hasMany(ReturnProduct::class);
		/* return $this->hasMany(ReturnProduct::class)->where('status','active'); */
	}

	public function productReductions()
	{
		return $this->hasMany(EditProductStock::class)->where('qty', '>', 0);
	}
}
