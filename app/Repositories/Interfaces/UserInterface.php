<?php

namespace App\Repositories\Interfaces;

interface UserInterface extends BaseInterface {

    public function updatePermissions($id, $permissions, $roles);
}
