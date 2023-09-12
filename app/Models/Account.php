<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Authenticatable
{
    use HasFactory;
    protected $fillable = ["login", "password", "role"];

    function getRole($role)
    {
        switch ($role) {
            case 0:
                return "Admin";
            case 1:
                return "Logistique";
            case 2:
                return "Fournisseur";
        }
    }

    public function getSubContractor()
    {
        return $this->query()->where("role", "=", 2)->get();
    }
    public function getDeliverer()
    {
        return $this->query()->where("role", "=", 1)->get();
    }

    function prestations(): HasMany
    {
        return $this->hasMany(SubOrder::class, "user_id");
    }
}
