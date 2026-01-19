<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Personel_history extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'personel_histories';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'personel_histories';
	protected $dates = ['deleted_at'];

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "personel_id", "id");
    }

    public function approve(){
        return $this->belongsTo(User::class, "must_approved_by", "id");
    }
}
