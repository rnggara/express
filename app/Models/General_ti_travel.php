<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class General_ti_travel extends Model
{
    use SoftDeletes;
    protected $table='general_ti_travel';
    protected $dates=['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_ti_travel';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
