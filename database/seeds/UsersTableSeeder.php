<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder {

    // Lors du déploiement de l'application on créait un utilisateur par défaut qui sera le SuperAdmin, 
    // un peu comme si tu te rendais chez un client pour déployer son application, il faut un type d'utilisateur qui ne sera pas utiliser dans l'application un superAdmin, mais ça dépend cv'est pas obligé.

    public function run() {
        $user = User::create(
            [
                'name' => 'admin',
                'password' => Hash::make('123123'),
                'email' => 'admin@bitchest.com'
            ]
        );

        // Pour gérer les roles et les permissions on a utilisé un package Laravel appalé spatie
        // Les tabels que j'ai cochés sont ceux installé par spaties qui permete de gérer les roles et permissions.
        // https://github.com/spatie/laravel-permission : tu peux ajouter cela dans ton rapport final comme référe,ce
        $role = Role::create(['name' => 'SuperAdmin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }

}

