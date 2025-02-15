<?php

namespace App\Repositories\Interfaces;

interface AuthInterface extends BaseInterface {

    public function register(array $data);

    public function login(array $data);

    public function forgot(string $email);

    public function reset(array $data);

    public function profile();

    public function update(array $data);

    public function logout();

    public function upload($path, $file);

}
