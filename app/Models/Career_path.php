<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Career_path extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'rms_career_path';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $table = 'rms_career_path';
}
