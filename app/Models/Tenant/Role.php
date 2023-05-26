<?php

namespace App\Models\Tenant;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use OnTenantIfDefined;

//    protected $guard_name = 'tenant';

}
