<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Kjk_lead_layout extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_lead_layouts';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function funnel() : HasMany {
        return $this->hasMany(Kjk_crm_lead_funnel::class, "layout_id", "id");
    }

    public function opportunity() : HasMany {
        return $this->hasMany(Marketing_leads::class, "layout_id", "id");
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    public function scopeDefault($q){
        return $q->where('status', 1);
    }

	protected $table = 'kjk_lead_layouts';
	protected $dates = ['deleted_at'];
}
