<?php

namespace App\Models\Bdgo;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class DpaStatus extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status'            => 'string',
        'customer_type_id'  => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'customer_type_id'
    ];

    public function scopeCustomerType($query)
    {
        $customerTypeId = Helper::getCustomerTypeId();

        if (!empty($customerTypeId) && $query->clone()->where('customer_type_id', $customerTypeId)->exists()) {
            return $query->where('customer_type_id', $customerTypeId);
        }

        return $query;
    }
}
