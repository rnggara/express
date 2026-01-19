<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_dashboard_pref extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_dashboard_pref';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $fillable = ["dashboard_key", "company_id"];

	protected $table = 'kjk_dashboard_pref';
	protected $dates = ['deleted_at'];
}
