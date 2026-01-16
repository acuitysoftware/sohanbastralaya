<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditProductStock extends Model
{
    use HasFactory;
    protected $table = 'st_edit_product_stock';
	protected $guarded = [];
	public $timestamps = false;
}
