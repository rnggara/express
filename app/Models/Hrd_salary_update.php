<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hrd_salary_update extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_salary_update';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_salary_update';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
