<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditProductStock2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'st_edit_product_stock';
	protected $guarded = [];
	public $timestamps = false;
}
