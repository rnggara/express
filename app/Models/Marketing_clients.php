<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class Marketing_clients extends Model
{
    use SoftDeletes;
    protected $table = 'marketing_clients';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'marketing_clients';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        'phones' => 'array',
        'emails' => "array"
    ];

    protected $fillable = [
        'company_name',
        "category",
        "type",
        "jumlah_karyawan",
        "pic_number",
        "email",
        "address",
        "company_id",
        "created_at",
        "created_by",
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

    public function opportunity(){
        return $this->hasMany(Marketing_leads::class, "id_client", "id");
    }

    public function contacts_person(){
        return $this->hasMany(Kjk_crm_leads_contact::class, "comp_id", "id");
    }

    public function parent(){
        return $this->belongsTo(Marketing_clients::class, "company_parent", "id");
    }

    public function childs(){
        return $this->hasMany(Marketing_clients::class, "company_parent", "id");
    }
}
