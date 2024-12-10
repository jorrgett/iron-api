<?php

namespace App\Repositories;

interface BaseInterface
{

    public function getAll($data);
    public function create(array $data);
    public function getByField($field, $value, $operator = '=');
    public function destroy($id);
    public function UpdateById($id, array $data);
}
