<?php

namespace App\Providers;

use App\Model\Cour;
use App\Repositories\CryptoRepository;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

// ce service permet de générer le cour d'une crypto-monnaies chaque jour
class CryptoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @param CryptoRepository $cryptoRepository
     * @return void
     */
    public function boot(CryptoRepository $cryptoRepository)
    {
        $arrCrypto  =   $cryptoRepository->getAllCrypto();
        foreach ($arrCrypto as $crypto) {
            $cour   =   Cour::where(['crypto_id' => $crypto->id])->whereDay('created_at', Carbon::now()->day)->whereMonth('created_at', Carbon::now()->month)->first();
            if (!$cour) {
                $current_cour = 0;
                do {
                    $current_cour   =   \App\Lib\Crypto::getCotationFor($crypto->name);
                } while ($current_cour < 0 || $current_cour == null);

                Cour::create(
                    [
                        'crypto_id' => $crypto->id,
                        'value' =>  $current_cour
                    ]
                );
            }
        }
    }
}
