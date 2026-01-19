<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Express_book_search extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'express_book_search';

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

	protected $table = 'express_book_search';
	protected $dates = ['deleted_at'];

    protected $casts = [
        "content" => "array",
        "kategori_id" => "array"
    ];

    public function tujuan(){
        return $this->belongsTo(Express_negara_tujuan::class, "tujuan_id");
    }

    public function dari(){
        return $this->belongsTo(Express_from::class, "dari_id");
    }

    public function produk(){
        return $this->belongsTo(Express_produk_tipe::class, "produk_id");
    }

    public function vendor(){
        return $this->belongsTo(Express_vendor::class, "vendor_id");
    }
}
