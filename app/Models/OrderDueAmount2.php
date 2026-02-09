<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDueAmount2 extends Model
{
    use HasFactory;
     protected $connection = 'mysql2';
     protected $table = 'order_due_amounts';
    protected $guarded = [];
}
