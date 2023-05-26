<?php

namespace App\Models\Bdgo;

use Illuminate\Database\Eloquent\Model;

class DeletionControl extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'control' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'control'
    ];
}
