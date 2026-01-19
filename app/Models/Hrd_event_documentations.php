<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hrd_event_documentations extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_event_documentations';
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_event_documentations';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
