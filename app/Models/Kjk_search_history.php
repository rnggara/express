<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_search_history extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_search_histories';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        "lokasi" => "array",
        "spec" => "array",
        "job_type" => "array",
        "salary" => "array",
        "edu" => "array",
    ];

	protected $table = 'kjk_search_histories';
	protected $dates = ['deleted_at'];
}
