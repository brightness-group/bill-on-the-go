<?php

namespace App\Models\Bdgo;

use Illuminate\Database\Eloquent\Model;

class StatusType extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status'         => 'string',
        'description'    => 'string',
        'order_position' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'description', 'order_position'
    ];
}
