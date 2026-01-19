<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class User_attendance extends Model
{
    use SoftDeletes;
    protected $table = 'user_attendance';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'user_attendance';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $fillable = [
        "user_id", "clock_type", "clock_time", "location_type", "company_id", "machine_id", "reason_id", "shift_id", "is_late", "created_at", "personel"
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('mobile', function (Builder $builder) {
            $builder->whereNull("machine_id");
        });
    }
}
