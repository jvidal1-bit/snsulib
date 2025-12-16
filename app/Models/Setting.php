<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'library_name',
        'operation_hours',
        'library_contact',
        'library_email',
        'library_address',
        'max_chapters_per_request',
        'request_expiry_days',
        'notify_on_request',
        'notify_on_complete',
        'notify_on_expiry',
        'footer_text',
    ];
}
