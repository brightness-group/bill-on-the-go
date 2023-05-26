<?php

namespace App\Models\Tenant\File;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileConnection extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = [
        'file_id', 'connection_id', 'start_date', 'end_date', 'bdgogid', 'groupname', 'userid', 'username', 'device_id',
        'devicename', 'billing_state', 'notes', 'activity_report', 'processed', 'cont_id', 'contact_type', 'tariff_id'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];
}
