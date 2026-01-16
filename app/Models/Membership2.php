<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership2 extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'st_memberships';
	protected $guarded = [];
	public $timestamps = false;

	public function order()
	{
		return $this->hasOne(ProductOrderDetails2::class, 'order_id', 'order_id');
	}
}
