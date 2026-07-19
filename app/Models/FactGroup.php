<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FactGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function facts(): HasMany
    {
        return $this->hasMany(Fact::class);
    }
}
