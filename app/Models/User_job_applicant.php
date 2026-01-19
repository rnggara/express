<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class User_job_applicant extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'user_job_applicants';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'user_job_applicants';
	protected $dates = ['deleted_at'];

    public function phone()
    {
        return $this->hasOne('App\Model\PhoneNumber')->latest();
    }

    public function interview() {
        return $this->hasOne(User_job_interview::class, 'job_app_id', "id")->orderBy("int_date", "desc")->orderBy("int_start", "desc");
    }
}
