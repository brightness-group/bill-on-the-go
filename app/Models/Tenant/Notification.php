<?php

namespace App\Models\Tenant;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    use OnTenantIfDefined;
}
