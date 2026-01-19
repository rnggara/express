<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_disc_desc_line extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_disc_desc_line';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_disc_desc_line';
	protected $dates = ['deleted_at'];

    public function scopeLine1($query)
    {
        return $query->where('code_line', 1);
    }

    public function scopeLine2($query)
    {
        return $query->where('code_line', 2);
    }

    public function scopeLine3($query)
    {
        return $query->where('code_line', 3);
    }
}
