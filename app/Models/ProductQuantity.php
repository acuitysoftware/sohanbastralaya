<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    use HasFactory;
    protected $table = 'st_product_quantity';
	protected $guarded = [];
	public $timestamps = false;

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
