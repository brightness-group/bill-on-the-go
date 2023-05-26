<?php

namespace App\Models\Tenant;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livetrack extends Model
{
    use HasFactory;
    use SoftDeletes;
    use OnTenantIfDefined;

    protected $fillable = ['user_id', 'user_name', 'start_date', 'end_date'];

    protected $dates = ['start_date', 'end_date', 'last_poll_date'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'bdgogid', 'bdgo_id');
    }
}
