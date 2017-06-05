<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all();

    public function withTrashed();

    public function onlyTrashed();

    public function makeModel();

    public function lists($column, $key = null);

    public function paginate($limit = null, $columns = ['*']);

    public function find($id, $columns = ['*']);

    public function where($conditions, $operator = null, $value = null);

    public function whereIn($column, $value);

    public function orWhere($column, $operator = null, $value = null);

    public function orWhereIn($column, $values);

    public function whereBetween($colunm, $values);

    public function whereNotIn($colunm, $values);

    public function whereNull($colunm);

    public function whereNotNull($colunm);

    public function create($input);

    public function firstOrCreate($input);

    public function multiCreate($input);

    public function update($id, $input);

    public function multiUpdate($column, $value, $input);

    public function delete($ids);

    public function join($tableName, $tableColumn, $modelColumn, $option = '');

    public function groupBy($colunms);

    public function count();

    public function first();

    public function with($relationship);

    public function orderBy($column, $option = 'asc');

    public function getModel();

    public function get();

    public function insertGetId($input);

    public function exists($column, $input);
}
