<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Att_overtime_record extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_overtime_record';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_overtime_record';
	protected $dates = ['deleted_at'];

    protected $casts = [
        "breaks" => "array"
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('mobile', function (Builder $builder) {
            $builder->whereNotIn("overtime_type", ["auto_in", "auto_out"]);
        });
    }

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "emp_id", "id");
    }

    public function reason(){
        return $this->belongsTo(Att_day_code::class, "reason_id", "id");
    }

    public function dept(){
        return $this->belongsTo(Kjk_comp_departement::class, "departement", "id");
    }
}
