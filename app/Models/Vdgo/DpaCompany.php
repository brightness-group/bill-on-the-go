<?php

namespace App\Models\Bdgo;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class DpaCompany extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'company'           => 'string',
        'category'          => 'string',
        'street'            => 'string',
        'postcode'          => 'string',
        'location'          => 'string',
        'email'             => 'string',
        'telephone'         => 'string',
        'website'           => 'string',
        'dpa_type_id'       => 'integer',
        'dpa_category_id'   => 'integer',
        'customer_type_id'  => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company', 'category', 'street', 'postcode', 'location', 'email', 'telephone', 'website', 'dpa_type_id', 'dpa_category_id', 'customer_type_id'
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
