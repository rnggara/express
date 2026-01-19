<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hrd_employee_attach_category extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_employee_attach_category';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_attach_category';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
