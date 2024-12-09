<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Remove the Jadwal policy registration
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
