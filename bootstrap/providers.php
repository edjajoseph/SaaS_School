<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    App\Core\Providers\TenancyServiceProvider::class,
    App\Core\Providers\ModuleServiceProvider::class,

    App\Modules\School\Providers\SchoolServiceProvider::class
];
