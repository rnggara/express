<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_holiday_category extends Model {

	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_holiday_categories';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_holiday_categories';
	protected $dates = ['deleted_at'];

}
