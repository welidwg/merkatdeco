<?php

namespace App\Models;

use App\Models\Source as ModelsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["client", "governorate_id", "address", "phone", "products", "details", "source", "status_id", "source_id",  "order_date", "delivery_date"];

    public static function countReady()
    {
        $status = Status::where("label", "Prête")->first();
        if ($status) {
            return self::where('status_id', $status->id)->count();
        }
        return [];
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class, "governorate_id");
    }


    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ModelsSource::class);
    }

    public function sub_orders(): HasMany
    {
        return $this->hasMany(SubOrder::class, "order_id");
    }

    function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
