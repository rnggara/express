<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Express_fuel_charge extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'express_fuel_charges';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \Auth::id() ?? null;
            $model->company_id = \Session::get('company_id') ?? null;
        });

        static::saving(function ($model) {
            $model->updated_by = \Auth::id() ?? null;
        });
        static::deleting(function ($model) {
            $model->deleted_by = \Auth::id() ?? null;
            $model->save();
        });
    }

	protected $table = 'express_fuel_charges';
	protected $dates = ['deleted_at'];
}
