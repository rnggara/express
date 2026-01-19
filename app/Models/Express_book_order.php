<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Express_book_order extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'express_book_order';

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

	protected $table = 'express_book_order';
	protected $dates = ['deleted_at'];
    protected $appends = ['base_price', 'sub_total', 'grand_total'];

    protected $casts = [
        "items" => "array",
        "promo_id" => "array",
        "promo_code" => "array"
    ];

    public function user(){
        return $this->belongsTo(User::class, "created_by");
    }

    public function book(){
        return $this->belongsTo(Express_book_search::class, "book_id");
    }

    public function getBasePriceAttribute(){
        return $this->biaya_kirim + $this->demand_surcharges + $this->green + $this->overweight + $this->oversize + $this->asuransi + $this->pickup_amount;
    }

    public function getSubTotalAttribute(){
        return $this->base_price + $this->fumigasi + $this->delivery_duty + $this->export_declare + $this->fuel_surcharge + $this->ncp + $this->nsu - $this->promo_amount;
    }

    public function getGrandTotalAttribute(){
        return $this->sub_total + $this->vat;
    }
}
