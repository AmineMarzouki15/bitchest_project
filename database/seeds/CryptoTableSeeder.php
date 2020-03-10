<?php


use App\Lib\Crypto as CryptoLib;
use App\Model\Crypto;

class CryptoTableSeeder extends \Illuminate\Database\Seeder
{

    public function run() {
        Crypto::create(['name' => 'Bitcoin', 'icon_name' => 'bitcoin', 'first_cour' => CryptoLib::getFirstCotation('Bitcoin')]);
        Crypto::create(['name' => 'Ethereum', 'icon_name' => 'ethereum', 'first_cour' => CryptoLib::getFirstCotation('Ethereum')]);
        Crypto::create(['name' => 'Ripple', 'icon_name' => 'ripple', 'first_cour' => CryptoLib::getFirstCotation('Ripple')]);
        Crypto::create(['name' => 'Bitcoin Cash', 'icon_name' => 'bitcoin-cash', 'first_cour' => CryptoLib::getFirstCotation('Bitcoin Cash')]);
        Crypto::create(['name' => 'Cardano', 'icon_name' => 'cardano', 'first_cour' => CryptoLib::getFirstCotation('Cardano')]);
        Crypto::create(['name' => 'Litecoin', 'icon_name' => 'litecoin', 'first_cour' => CryptoLib::getFirstCotation('Litecoin')]);
        Crypto::create(['name' => 'NEM', 'icon_name' => 'nem', 'first_cour' => CryptoLib::getFirstCotation('NEM')]);
        Crypto::create(['name' => 'Stellar', 'icon_name' => 'stellar', 'first_cour' => CryptoLib::getFirstCotation('Stellar')]);
        Crypto::create(['name' => 'IOTA', 'icon_name' => 'iota', 'first_cour' => CryptoLib::getFirstCotation('IOTA')]);
        Crypto::create(['name' => 'Dash', 'icon_name' => 'dash', 'first_cour' => CryptoLib::getFirstCotation('Dash')]);
    }
}
