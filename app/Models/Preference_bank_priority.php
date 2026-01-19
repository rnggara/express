<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preference_bank_priority extends Model
{
    use SoftDeletes;
    protected $table = 'preference_bank_priority';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'preference_bank_priority';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
