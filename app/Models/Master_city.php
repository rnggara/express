<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_city extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];

    protected $table = "master_cities";
}
