<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'account_number',
        'key',
        'qr_path',
        'created_by',
    ];
}
