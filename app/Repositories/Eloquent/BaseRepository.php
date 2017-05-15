<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $app;

    public function __construct()
    {
        $this->app = new App();
        $this->makeModel();
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * function all to get all data of model.
     *
     * @return void
    */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * to get columns that delete used soft delete.
     *
     * @return void
    */
    public function withTrashed()
    {
        return $this->model->withTrashed();
    }

    /**
    * to get column that delete used soft delete.
     *
     * @return void
    */
    public function onlyTrashed()
    {
        return $this->model->onlyTrashed();
    }

    /**
     * to get lists columns.
     *
     * @return void
    */
    public function lists($column, $key = null)
    {
        return $this->model->pluck($column, $key);
    }

    public function paginate($limit = null, $columns = ['*'])
    {
        $limit = $limit ?: config('setting.paginate');

        return $this->model->paginate($limit, $columns);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $this->model->whereIn($column, $values);

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null)
    {
        $this->model->orWhere($column, $operator, $value);

        return $this;
    }

    public function orWhereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $this->model->orWhereIn($column, $values);

        return $this;
    }

    public function where($conditions, $operator = null, $value = null)
    {
        $this->model->where($conditions, $operator, $value);

        return $this;
    }

    public function whereBetween($colunm, $values)
    {
        return $this->model->whereBetween($colunm, $values);
    }

    public function whereNotIn($colunm, $values)
    {
        return $this->model->whereNotIn($colunm, $values);
    }

    public function whereNull($colunm)
    {
        return $this->model->whereNull($colunm);
    }

    public function whereNotNull($colunm)
    {
        return $this->model->whereNotNull($colunm);
    }

    public function create($input)
    {
        return $this->model->insertGetId($input);
    }

    public function firstOrCreate($input)
    {
        return $this->model->firstOrCreate($input);
    }

    public function multiCreate($input)
    {
        return $this->model->insert($input);
    }

    public function update($id, $input)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($input);
        $model->save();

        return $this;
    }

    public function multiUpdate($column, $value, $input)
    {
        $value = is_array($value) ? $value : [$value];

        return $this->model->whereIn($column, $value)->update($input);
    }

    public function delete($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];

        return $this->model->whereIn('id', $ids)->delete();
    }

    public function orderBy($column, $option = 'asc')
    {
        return $this->model->orderBy($column, $option);
    }

    public function join($tableName, $tableColumn, $modelColumn, $option = '')
    {
        switch ($option) {
            case 'leftJoin':
                return $this->model->leftJoin($tableName, $tableColumn, $modelColumn);

            case 'rightJoin':
                return $this->model->rightJoin($tableName, $tableColumn, $modelColumn);

            default:
                return $this->model->join($tableName, $tableColumn, $modelColumn);
        }
    }

    public function groupBy($colunms)
    {
        $colunms = is_array($colunms) ? $colunms : [$colunms];

        return $this->model->groupBy($colunms);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function with($relationship)
    {
        return $this->model->with($relationship);
    }
}
