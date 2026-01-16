<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $table = 'st_memberships';
	protected $guarded = [];
	public $timestamps = false;

	public function order()
	{
		return $this->hasOne(ProductOrderDetails::class, 'order_id', 'order_id');
	}
}
