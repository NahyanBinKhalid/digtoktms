<?php

namespace App\Repositories\Interfaces;

interface BaseInterface {

    public function dataObject();

    public function all();

    public function allDataTables();

    public function allPlucked($value, $key = null);

    public function count();

    public function create(array $data);

    public function createMultiple(array $data);

    public function delete();

    public function deleteById($id);

    public function deleteByUuid($uuid);

    public function deleteMultipleById(array $ids);

    public function deleteMultipleByUuid(array $uuids);

    public function first();
    
    public function firstOrFail();

    public function get();

    public function getById($id);

    public function getByUuid($uuid);

    public function activateToggleById($id);

    public function activateToggleByUuid($uuid);

    public function limit($limit);

    public function orderBy($column, $value);

    public function updateById($id, array $data);

    public function updateByUuid($uuid, array $data);

    public function select($columns);

    public function where($column, $value, $operator = '=');

    public function whereIn($column, $value);

    public function with($relations);

    public function dataTables();

    public function pluck($column, $key = null);
}
