<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Marketing_leads extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_leads';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_leads';

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

    public function funnel(){
        return $this->belongsTo(Kjk_crm_lead_funnel::class, "funnel_id", "id");
    }
}
