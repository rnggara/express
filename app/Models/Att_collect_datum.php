<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_collect_datum extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_collect_data';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_collect_data';
	protected $dates = ['deleted_at'];

    protected $casts = [
        'att_data' => "array"
    ];

    public function machine(){
        return $this->belongsTo(Att_machine_name::class, "machine_id", "id");
    }
}
