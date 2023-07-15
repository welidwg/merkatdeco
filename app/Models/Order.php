<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["client", "governorate_id", "address", "phone", "products", "details", "status_id", "order_date", "delivery_date"];


    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class, "governorate_id");
    }


    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, "status_id");
    }


    public function sub_orders(): HasMany
    {
        return $this->hasMany(SubOrder::class, "order_id");
    }
}
