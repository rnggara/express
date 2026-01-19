<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pref_uom extends Model
{
    use SoftDeletes;
    protected $table = 'pref_uom';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'pref_uom';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
