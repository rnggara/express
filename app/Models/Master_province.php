<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_province extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];

    protected $table = "master_provinces";

    public function cities(): HasMany
    {
        return $this->hasMany(Master_city::class, 'prov_id', 'id');
    }
}
