<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Admin extends Model
{
	protected $table = 'st_admin';
	protected $guarded = [];
	public $timestamps = false;
    use HasFactory;
}
