<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_onboarding_form extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_onboarding_forms';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_onboarding_forms';
	protected $dates = ['deleted_at'];

    public function picData()
    {
        return $this->belongsTo(User::class, "pic", "id");
    }
}
