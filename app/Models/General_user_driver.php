<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class General_user_driver extends Model
{
    use SoftDeletes;
    protected $table= 'general_user_driver';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'general_user_driver';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function scopeStaff($query){
        return $query->where("type", 2);
    }

    public function scopeDriver($query){
        return $query->where("type", 1);
    }

    public function scopeBot($query, $token){
        $value = 1;
        if($token == "5874227125:AAG0fxiMkmWCdoxMWJ0Efyahu4EpP500ofM"){
            $value = 2;
        }
        return $query->where("type", $value);
    }
}
