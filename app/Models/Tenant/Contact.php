<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Session\ContactUserSession;
use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = ['salutation', 'firstname', 'lastname', 'c_department', 'c_function', 's_email', 'p_email', 'b_number', 'm_number', 'h_number', 'bdgo_gid', 'device_id', 'mobile_id'];

    protected $appends = ['full_name'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'bdgo_gid', 'bdgogid');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class);
    }

    public function contact_user_session()
    {
        return $this->hasMany(ContactUserSession::class);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }
}
