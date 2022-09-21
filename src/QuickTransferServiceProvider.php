<?php

namespace SlickPay\QuickTransfer;

use Illuminate\Support\ServiceProvider;

class QuickTransferServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/quick-transfer.php' => config_path('quick-transfer.php'),
        ], 'quick-transfer-config');
    }

    public function register()
    {
        //
    }
}
