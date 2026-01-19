<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_crm_job_title extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_job_titles';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function children() {
        return $this->hasMany(Kjk_crm_job_title::class, "parent_id", "id");
    }

    public function parent() {
        return $this->belongsTo(Kjk_crm_job_title::class, "id", "parent_id");
    }

	protected $table = 'kjk_crm_job_titles';
	protected $dates = ['deleted_at'];
}
