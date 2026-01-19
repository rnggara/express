<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_rate_multiplier extends Model
{
    use SoftDeletes;
    protected $table = 'master_rate_multiplier';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'master_rate_multiplier';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
