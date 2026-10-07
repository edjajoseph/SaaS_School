<?php

// app/Modules/Hotel/HotelServiceProvider.php

namespace App\Modules\RH\Providers;

use Illuminate\Support\ServiceProvider;

class RHServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Exemple : Binding d'interface spécifique au module
        // $this->app->bind(PaymentGatewayInterface::class, StripeHotelGateway::class);
    }

    public function boot(): void
    {
        // Code spécifique exécuté au boot de ce module uniquement
    }
}