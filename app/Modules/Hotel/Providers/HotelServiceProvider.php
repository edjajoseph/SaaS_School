<?php

// app/Modules/Hotel/HotelServiceProvider.php

namespace App\Modules\Hotel;

use Illuminate\Support\ServiceProvider;

class HotelServiceProvider extends ServiceProvider
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