<?php

namespace App\Providers;

use App\Models\Placement;
use App\Models\Refugee;
use App\Models\RefugeeDocument;
use App\Policies\PlacementPolicy;
use App\Policies\RefugeeDocumentPolicy;
use App\Policies\RefugeePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Refugee::class => RefugeePolicy::class,
        Placement::class => PlacementPolicy::class,
        RefugeeDocument::class => RefugeeDocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
