<?php

namespace App\Repositories;

interface BaseInterfaceWithSP
{

    public function getAll($data);
    public function getByField($field, $value, $operator = '=');
    public function destroy($id);
}
