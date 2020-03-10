<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository {

    /**
     * Undocumented function
     *
     * @param Role $role
     */
    public $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function getAllRole() {
        return $this->role::pluck('name', 'name')->all();
    }

}


