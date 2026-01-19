<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Personel_offence extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'personel_offence';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'personel_offence';
	protected $dates = ['deleted_at'];

    public function detail(){
        return $this->belongsTo(Kjk_comp_offence::class, "offence_reason", "id");
    }

    public function user(){
        return $this->belongsTo(User::class, "given_by", "id");
    }
}
