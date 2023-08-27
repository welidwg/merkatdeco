<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_status extends Model
{
    use HasFactory;
    protected $fillable = ["label", "class"];
    
}
