<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_list_note extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_list_notes';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_list_notes';
	protected $dates = ['deleted_at'];
}
