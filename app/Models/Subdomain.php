<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    protected $fillable = ['subdomain', 'description', 'target'];
    use HasFactory;
}
