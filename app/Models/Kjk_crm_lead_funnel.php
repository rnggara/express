<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Session;

class Kjk_crm_lead_funnel extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_lead_funnel';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function pipeline()
	{
		return $this->belongsTo(Kjk_lead_layout::class, 'id', 'layout_id');
	}

    public function opportunity() : HasMany {
        return $this->hasMany(Marketing_leads::class, "funnel_id", "id");
    }

	protected $table = 'kjk_crm_lead_funnel';
	protected $dates = ['deleted_at'];
}
