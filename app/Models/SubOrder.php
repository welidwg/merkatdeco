<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubOrder extends Model
{
    use HasFactory;
    protected $fillable = ["order_id", "subcontractor", "phone", "pieces", "start_date", "end_date", "status_id"];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }
}
