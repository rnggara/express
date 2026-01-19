<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_employee_test extends Model
{
    use SoftDeletes;

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_test';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $table = 'hrd_employee_test';
    protected $dates = ['deleted_at'];

    public function questions(): HasMany
    {
        return $this->hasMany(Hrd_employee_question::class, "test_id", "id");
    }

    public function results(): HasMany
    {
        return $this->hasMany(Hrd_employee_test_result::class, "test_id", "id");
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Hrd_employee_test_materials::class, "test_id", "id");
    }

    public function category(): BelongsTo
    {
        return $this->BelongsTo(Hrd_employee_test_category::class, "category_id", "id");
    }
}
