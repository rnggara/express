<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Express_pickup_request extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'express_pickup_request';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
    protected $table = 'express_pickup_request';
    protected $dates = ['deleted_at'];
}
