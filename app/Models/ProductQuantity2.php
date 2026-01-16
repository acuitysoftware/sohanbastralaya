<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantity2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'st_product_quantity';
	protected $guarded = [];
	public $timestamps = false;

	public function product()
	{
		return $this->belongsTo(Product2::class, 'product_id', 'id');
	}
}
