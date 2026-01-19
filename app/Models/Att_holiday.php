<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_holiday extends Model {

	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_holidays';

    protected $fillable = [
        'holiday_date',
        'name',
        'category_id',
        'created_at',
        'created_by',
        'company_id',
    ];

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_holidays';
	protected $dates = ['deleted_at'];

    public function category(){
        return $this->belongsTo(Att_holiday_category::class, "category_id", "id");
    }
}
