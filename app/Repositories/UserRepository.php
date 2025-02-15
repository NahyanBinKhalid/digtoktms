<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserInterface;

class UserRepository extends BaseRepository implements UserInterface {
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function updatePermissions($uuid, $permissions, $roles)
    {
        $user = $this->model->where('uuid', $uuid)->first();
        $user->syncPermissions($permissions);
        $user->syncRoles($roles);
    }
}
