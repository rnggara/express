<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_reason_condition extends Model {

	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_reason_conditions';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        "conditions" => "array",
        'shift_code' => "array",
        'rp_detail' => "array"
    ];
	protected $table = 'att_reason_conditions';
	protected $dates = ['deleted_at'];

    public function reasonName() {
        return $this->belongsTo(Att_reason_name::class, "reason_name_id", "id");
    }

    public function reasonType() {
        return $this->belongsTo(Att_reason_type::class, "reason_type_id", "id");
    }

}
