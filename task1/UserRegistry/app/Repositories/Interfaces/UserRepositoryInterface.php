<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findAll();
    public function create(array $data);
    public function delete($id);
}
