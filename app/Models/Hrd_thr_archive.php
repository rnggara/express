<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_thr_archive extends Model
{

    protected $table = 'hrd_thr_archive';

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_thr_archive';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
