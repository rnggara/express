<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Job_vacancy_model extends Model
{
    use SoftDeletes;
    protected $table = 'general_meeting_zoom';
    protected $dates = ['deleted_at'];
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_meeting_zoom';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
