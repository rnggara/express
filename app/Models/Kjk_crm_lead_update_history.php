<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_crm_lead_update_history extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_lead_update_histories';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_crm_lead_update_histories';
	protected $dates = ['deleted_at'];

    public function scopeOpportunity($query){
        return $query->where("type", "opportunity");
    }

    public function scopeCompany($query){
        return $query->where("type", "company");
    }

    public function scopeContact($query){
        return $query->where("type", "contact");
    }

    public function scopeFiles($query){
        return $query->where("type", "files");
    }

    public function scopeProduct($query){
        return $query->where("type", "product");
    }
}
