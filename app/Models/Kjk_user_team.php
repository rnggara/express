<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_user_team extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_user_teams';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        "members" => "array"
    ];

	protected $table = 'kjk_user_teams';
	protected $dates = ['deleted_at'];

    public function pipeline(){
        return $this->belongsTo(Kjk_lead_layout::class, "pipeline_id", "id");
    }
}
