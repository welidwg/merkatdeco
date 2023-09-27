<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "content", "chat_id"];

    function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, "chat_id");
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(Account::class, "user_id");
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'ASC');
        });
    }
}
