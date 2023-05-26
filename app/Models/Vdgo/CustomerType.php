<?php

namespace App\Models\Bdgo;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];
}
