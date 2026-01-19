<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_machine_name extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_machine_names';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_machine_names';
	protected $dates = ['deleted_at'];

    protected $casts = [
        "absensi_code" => "array",
        "id_card" => "array",
        "date_format" => "array",
        "time_format" => "array",
    ];

    public function program(){
        return $this->belongsTo(Att_machine_type::class, "program_id", "id");
    }
}
