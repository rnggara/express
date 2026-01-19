<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_crm_tag extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_tags';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $fillable = ["label", "category"];

	protected $table = 'kjk_crm_tags';
	protected $dates = ['deleted_at'];
}
