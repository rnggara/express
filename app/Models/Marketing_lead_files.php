<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Marketing_lead_files extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_lead_files';
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_lead_files';

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

    public function opportunity() {
        return $this->belongsTo(Marketing_leads::class, "id_lead", "id");
    }

}
