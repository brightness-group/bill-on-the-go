<?php

namespace App\Models\Bdgo;

use Illuminate\Database\Eloquent\Model;

class DeletionType extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string'
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
