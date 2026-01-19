<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Preference_bank extends Model
{
    use SoftDeletes;
    protected $table = 'preference_bank';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'preference_bank';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
