<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['transaction_reference', 'raw_response', 'action_taken'];
}
