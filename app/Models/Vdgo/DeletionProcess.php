<?php

namespace App\Models\Bdgo;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class DeletionProcess extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'process'                   => 'string',
        'process_description'       => 'string',
        'month'                     => 'integer',
        'other'                     => 'string',
        'template_id'               => 'integer',
        'department_category_id'    => 'integer',
        'customer_type_id'          => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'process', 'process_description', 'month', 'other', 'template_id', 'department_category_id', 'customer_type_id'
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
