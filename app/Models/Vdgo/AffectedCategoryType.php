<?php

namespace App\Models\Bdgo;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class AffectedCategoryType extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'             => 'string',
        'language_code'    => 'string',
        'customer_type_id' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'language_code', 'customer_type_id'
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
