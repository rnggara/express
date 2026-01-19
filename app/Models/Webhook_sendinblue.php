<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook_sendinblue extends Model
{
    protected $table = 'webhook_sendinblue';

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'webhook_sendinblue';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
