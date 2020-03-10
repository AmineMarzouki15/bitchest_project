<?php


class CourTableSeeder extends \Illuminate\Database\Seeder
{

// lors du déploiement de l'application en exécutant la commande php artisan migrate:refresh --seed de laravel
    // la table des cours se charge à ce niveau 
    public function run() {
        $cryptos    =   \App\Model\Crypto::all();
        $current_date   =   \Carbon\Carbon::now();
        // on parcour la liste des cryptomonnaies
        foreach ($cryptos as $crypto) {
            for ($i = 29 ; $i >= 0 ; $i--) {
                $current_cour   = 0;
                $sub_date   = $current_date->copy()->subDays($i);
                // on boucle sur les 29 jours précédent à partir de la date actuel 
                do {
                    // c'est ici qu'on génère le cour de chaque journée en respectant la condition qui dit que le cour doit être toujours positif
                    $current_cour   =   \App\Lib\Crypto::getCotationFor($crypto->name);
                } while ($current_cour < 0 || $current_cour == null);

                \App\Model\Cour::create(
                    [
                        'crypto_id' => $crypto->id,
                        'value' =>  $current_cour,
                        'created_at'    => $sub_date,
                        'updated_at' => $sub_date
                    ]
                );
            }
        }

    }
}
