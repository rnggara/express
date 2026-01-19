<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hrd_employee_bank extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_employee_bank';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_bank';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
