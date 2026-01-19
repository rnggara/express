<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Express_promo extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'express_promo';

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

	protected $table = 'express_promo';
	protected $dates = ['deleted_at'];
    protected $appends = ["formula_text"];

    public function getFormulaTextAttribute(){
        return "$".$this->target." ".$this->formula;
    }
}
