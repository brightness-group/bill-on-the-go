<?php


namespace App\Actions\Fortify;

use Closure;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;

use Illuminate\Support\Str;
use Tenancy\Facades\Tenancy;

class PasswordBrokerManager extends \Illuminate\Auth\Passwords\PasswordBrokerManager implements FactoryContract
{
    /**
     * Attempt to get the broker from the local cache.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();
        if (Tenancy::getTenant()) {
            return $this->brokers[$name[1]] ?? ($this->brokers[$name[1]] = $this->resolve($name[1]));
        }
        else
            return $this->brokers[$name[0]] ?? ($this->brokers[$name[0]] = $this->resolve($name[0]));
    }

    /**
     * Create a token repository instance based on the given configuration.
     *
     * @param  array  $config
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        if ($tenant = Tenancy::getTenant())
            $connection = 'tenant';
        else
            $connection = $config['connection'] ?? null;
        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        );
    }

    public function sendResetLink(array $credentials, Closure $callback = null)
    {
        // TODO: Implement sendResetLink() method.
    }

    public function reset(array $credentials, Closure $callback)
    {
        // TODO: Implement reset() method.
    }


}
