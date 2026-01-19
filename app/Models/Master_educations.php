<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_educations extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];

    protected $table = "master_educations";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->whereNull("company_id");
        });
    }

}
