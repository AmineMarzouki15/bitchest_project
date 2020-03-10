<?php


use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{

    public function run() {

        $arrRole = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        $arrUser = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete'
        ];

        $arrCrypto = [
            'crypto-list'
        ];

        $permissions = array_merge($arrRole, $arrUser, $arrCrypto);

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }
    }
}
