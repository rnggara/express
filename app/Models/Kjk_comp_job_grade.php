<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_comp_job_grade extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_comp_job_grade';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_comp_job_grade';
	protected $dates = ['deleted_at'];
}
