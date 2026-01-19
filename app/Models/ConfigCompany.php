<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ConfigCompany extends Model
{
    //
    use LogsActivity;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'config_company';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $table = 'config_company';

    public function parentComp(){
        return $this->belongsTo(ConfigCompany::class, "id_parent", "id");
    }
}
