<?php

namespace App\Models\Tenant;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTariff extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = ['id', 'customer_id','tariff_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_tariff';

}
