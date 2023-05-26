<?php

namespace App\Models\Tenant\File;

use App\Models\Tenant\SharedUser;
use App\Traits\OnTenantIfDefined;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    use OnTenantIfDefined;

    protected $fillable = [
        'original_name', 'path_to_file', 'user_id', 'uploaded'
    ];

    public function uploaded_connections()
    {
        return $this->hasMany(FileConnection::class);
    }

    public function shared_users()
    {
        return $this->belongsToMany(SharedUser::class);
    }
}
