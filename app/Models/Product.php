<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ["title", "stock", "category_id", "sub_category_id", "measures", "details", "colors"];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id");
    }


    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, "sub_category_id");
    }
}
