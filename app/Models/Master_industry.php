<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_industry extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];

    protected $table = "master_industries";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->whereNull("company_id");
        });
    }

    public function scopeHris($query, $company_id)
    {
        return $query->withoutGlobalScope('status')->where(function($q) use($company_id){
            $q->whereNull("company_id");
            $q->orWhere("company_id", $company_id);
        });
    }
}
