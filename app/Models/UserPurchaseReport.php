<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPurchaseReport extends Model
{
    use HasFactory;
    protected $table = 'st_user_purchase_report';
	protected $guarded = [];
	public $timestamps = false;
}
