<?php

namespace App\Models\Tenant\Session;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUserSession extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = ['user_id', 'contact_id', 'session_id'];

}
