<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_crm_product extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_products';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            $builder->where('archive_at', null);
        });
    }
    public function scopeArchived($query){
        return $query->whereNotNull("archive_at");
    }

	protected $table = 'kjk_crm_products';
	protected $dates = ['deleted_at'];
}
