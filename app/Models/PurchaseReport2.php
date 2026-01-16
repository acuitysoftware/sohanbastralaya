<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReport2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'st_purchase_report';
	protected $guarded = [];
	public $timestamps = false;
}
