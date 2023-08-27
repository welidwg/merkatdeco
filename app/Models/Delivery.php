<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = ["order_id", "affected_date", "user_id", "status_id", "end_date"];

    function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    function status(): BelongsTo
    {
        return $this->belongsTo(Delivery_status::class, "status_id");
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, "user_id");
    }
}
