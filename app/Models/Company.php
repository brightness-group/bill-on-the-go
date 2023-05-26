<?php

namespace App\Models;

use App\Models\Bdgo\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Input\InputInterface;
use Illuminate\Support\Facades\Crypt;

use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;

use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use \Tenancy\Tenant\Events\Created;
use \Tenancy\Tenant\Events\Updated;
use \Tenancy\Tenant\Events\Deleted;


class Company extends Model implements Tenant, IdentifiesByHttp, IdentifiesByQueue, IdentifiesByConsole   //  , ManagesSystemConnection
{
    use HasFactory;
    use AllowsTenantIdentification;

    const DEFAULT_CUSTOMER_TYPE_ID = NULL;

    protected $fillable = [
        'name',
        'subdomain',
        'logo',
        'address',
        'zip',
        'city',
        'country',
        'phone',
        'email',
        'contact',
        'contact_email',
        'website',
        'notes',
        'billing_address',
        'payment',
        'iban',
        'bic',
        'tax_number',
        'status',
        'anydesk_client_id',
        'anydesk_client_secret',
        'customer_type_id',
        'sync_cron_date',
        'tv_sync_in_progress',
        'tv_sync_batch_id',
        'batch_id',
        'rectangle_logo',
    ];

    protected $dates = ['anydesk_access_token_for_expire_check'];

    protected $dispatchesEvents = [
        'created' => Created::class,
        'updated' => Updated::class,
        'deleted' => Deleted::class,
    ];

    protected $append = ['customer_type'];

    public function generateFullURL()
    {
        $url = config('app.url');
        $env = config('app.app_subdomain');
        return str_replace($env, $this->subdomain, $url);
    }

    /**
    * Specify whether the tenant model is matching the request.
    *
    * @param Request $request
    * @return Tenant
    */
    public function tenantIdentificationByHttp(Request $request): ?Tenant
    {
        list($subdomain) = explode('.', $request->getHost(), 2);
        $tenant = null;
        if ($subdomain !== config('app.app_subdomain')) {
            $tenant = $this->query()->where('subdomain', $subdomain)->first();
            if ($tenant instanceof Tenant) {
                view()->share('tenant', $tenant);
            }
        }
        return $tenant;
    }

    public function tenantIdentificationByQueue(Processing $event): ?Tenant
    {
        if ($event->tenant) {
            return $event->tenant;
        }

        if ($event->tenant_key && $event->tenant_identifier === $this->getTenantIdentifier()) {
            return $this->newQuery()
                ->where($this->getTenantKeyName(), $event->tenant_key)
                ->first();
        }
        return null;
    }

    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param InputInterface $input
     * @return Tenant
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Tenant
    {
        if ($input->hasParameterOption('--tenant-identifier')) {
            if($input->getParameterOption('--tenant-identifier') != $this->getTenantIdentifier()) {
                return null;
            }
        }

        if ($input->hasParameterOption('--tenant')) {
            return $this->query()
                ->where('subdomain', $input->getParameterOption('--tenant'))
                ->first();
        }
        return null;
    }

    /**
     * The attribute of the Model to use for the key.
     *
     * @return string
     */
    public function getTenantKeyName(): string
    {
        return 'id';
    }

    /**
     * The actual value of the key for the tenant Model.
     *
     * @return string|int
     */
    public function getTenantKey() : int|string
    {
        return $this->id;
    }

    /**
     * A unique identifier, eg class or table to distinguish this tenant Model.
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return get_class($this);
    }

    public function getManagingSystemConnection(): ?string
    {
        return 'mysql';
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function setTeamviewerClientSecretAttribute($value):void
    {
        if (empty($value)) {
            $this->attributes['anydesk_client_secret'] = $value;
        }

        $this->attributes['anydesk_client_secret'] = Crypt::encryptString($value);
    }

    public function getTeamviewerClientSecretAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        return Crypt::decryptString($value);
    }

    public function customerType(): HasOne
    {
        return $this->hasOne(CustomerType::class, 'id', 'customer_type_id');
    }

    public function getCustomerTypeAttribute()
    {
        if (!empty($this->customer_type_id)) {
            $customerType = $this->customerType()->first();

            if (!empty($customerType)) {
                switch ($customerType->type) {
                    case "1":
                        return "church";
                        break;
                    case "2":
                        return "sme";
                        break;
                    case "3":
                        return "school";
                        break;
                    case "4":
                        return "authorities";
                        break;
                    case "5":
                        return "association";
                        break;
                    case "6":
                        return "health_care";
                        break;
                    case "7":
                        return "medical_care";
                        break;
                }
            }
        }

        return false;
    }
}
