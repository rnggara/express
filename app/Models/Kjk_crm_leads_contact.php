<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Kjk_crm_leads_contact extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'kjk_crm_leads_contacts';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'kjk_crm_leads_contacts';
	protected $dates = ['deleted_at'];

    protected $casts = [
        'phones' => 'array',
        'emails' => "array"
    ];

    protected $fillable = [
        "name",
        "position",
        "religion_id",
        "birth_date",
        "role",
        "no_telp",
        "email",
        "address",
        "company_id",
        "created_at",
        "created_by"
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            $builder->where('archive_at', null);
        });
    }
    public function scopeArchived($query){
        return $query->whereNotNull("archive_at");
    }
}
