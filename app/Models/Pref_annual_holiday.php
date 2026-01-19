<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Pref_annual_holiday extends Model
{
    use SoftDeletes;
    protected $table = 'pref_annual_holiday';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'pref_annual_holiday';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
