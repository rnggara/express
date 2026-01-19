<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_employer_bookmark extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_employer_bookmark';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $fillable = ["employer_id", "applicant_id"];

	protected $table = 'kjk_employer_bookmark';
	protected $dates = ['deleted_at'];
}
