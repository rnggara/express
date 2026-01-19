<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_employee_question extends Model
{
    use SoftDeletes;

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_question';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $table = 'hrd_employee_question';
    protected $dates = ['deleted_at'];

    public function points(): HasMany
    {
        return $this->hasMany(Hrd_employee_question_point::class, "question_id", "id");
    }
}
