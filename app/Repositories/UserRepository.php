<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserInterface;

class UserRepository extends BaseRepository implements UserInterface {
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
