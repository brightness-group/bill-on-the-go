<?php

namespace App\Models\Tenant;

use App\Models\Tenant\File\File;
use App\Models\Tenant\Session\ContactUserSession;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use App\Traits\OnTenantIfDefined;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable //implements HasLocalePreference
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use CanResetPassword;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use OnTenantIfDefined;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_photo_path', 'last_login_at', 'last_login_ip', 'is_allow_api'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'short_name',
    ];

//    public function preferredLocale()
//    {
//        return $this->locale;
//    }

    public function setPasswordAttribute($password)
     {
         $this->attributes['password'] = bcrypt($password);
     }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function contact_user_session()
    {
        return $this->hasMany(ContactUserSession::class);
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function connection_recovery()
    {
        return $this->hasOne(ConnectionRecovery::class);
    }

    public function getShortNameAttribute()
    {
        if (!empty($this->name)) {
            $arr = explode(' ', $this->name);
            $last_letter = !empty($arr[1]) ? substr($arr[1], 0, 1) : '';
            return substr($this->name, 0, 1) . $last_letter;
        }
        return null;
    }

    /**
     * Overrides from HasProfilePhoto class.
     * Because URL contains storage/storage twise.
     * We cant change store function.
     * 
     * Jaydeep Mor <j.mor@brightness-india.comx>
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url(str_replace('storage/', '', $this->profile_photo_path))
                    : $this->defaultProfilePhotoUrl();
    }
}
