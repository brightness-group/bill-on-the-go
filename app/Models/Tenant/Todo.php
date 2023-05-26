<?php

namespace App\Models\Tenant;

use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tenancy\Facades\Tenancy;

class Todo extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = ['id', 'user_id', 'type', 'todo', 'tags', 'is_completed', 'is_important', 'sort_order', 'data'];

    protected $table = 'todo';

    protected $casts = [
        'tags' => 'array',
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
