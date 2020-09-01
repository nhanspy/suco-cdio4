<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Model|Builder|SoftDeletes
     */
    public $model;

    /** @var string */
    protected $field;

    /** @var string */
    protected $value;

    protected $modelName;

    /**
     * BaseRepository constructor.
     *
     * @param App $app
     * @throws Exception
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->modelName = $this->getModelName();
    }

    /**
     * Specify Model
     * @return mixed
     */
    abstract public function model();

    /**
     * @return Model|mixed
     * @throws Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            $message= "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model";
            throw new Exception($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->model = $model;
    }

    /**
     * @return Model|mixed
     * @throws Exception
     */
    public function resetModel()
    {
        return $this->makeModel();
    }

    /**
     * get model name as a string
     * @return string
     */
    public function getModelName()
    {
        $class = explode('\\', get_class($this->model));

        return strtolower($class[count($class) - 1]);
    }

    /**
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }

    /**
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $result = $this->model->get($columns);

        $this->resetModel();

        return $result;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('exceptions.'.$this->modelName.'.not_found', 404);
        }
    }

    /**
     * @param array $columns
     * @return Collection
     * @throws Exception
     */
    public function first($columns = ['*'])
    {
        $result = $this->model->first($columns);

        $this->resetModel();

        return $result;
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function firstOrFail($field, $value, $columns = ['*'])
    {
        try {
            return $this->model->where($field, $value)->firstOrFail($columns);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('exceptions.'.$this->modelName.'.not_found', 404);
        }
    }

    /**
     * @return $this
     */
    public function onlyTrashed()
    {
        $this->model = $this->model->onlyTrashed();

        return $this;
    }

    /**
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update($id, array $data)
    {
        $model = $this->findOrFail($id);
        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * @param $id
     * @return int
     * @throws
     */
    public function delete($id = 0)
    {
        $result = $id ? $this->model->destroy($id) : $this->model->delete();

        $this->resetModel();

        return $result;
    }

    /**
     * @param $id
     * @return bool|mixed|null
     */
    public function forceDelete($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    /**
     * example use
     * [id => 1]
     * [['id', '=', 1]]
     * @param array $condition
     * @return $this
     */
    public function where(array $condition)
    {
        foreach ($condition as $field => $value) {
            if (is_array($value)) {
                list($field, $operator, $val) = $value;
                $this->model = $this->model->where($field, $operator, $val);
            } else {
                $this->model = $this->model->where($field, $value);
            }
        }

        return $this;
    }

    /**
     * @param $orderBy
     * @param string $sortBy
     * @return $this
     */
    public function orderBy($orderBy = 'created_at', $sortBy = 'asc')
    {
        $this->model = $this->model->orderBy($orderBy, $sortBy);

        return $this;
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function paginate($perPage = null, $columns = ['*'])
    {
        $perPage = (int)$perPage ?: config('repository.pagination.limit');
        $result = $this->model->paginate($perPage, $columns);
        $this->resetModel();

        return $result;
    }

    /**
     * @return $this
     */
    public function random()
    {
        $this->model = $this->model->inRandomOrder();

        return $this;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isExist($id)
    {
        if ($this->find($id)) {
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    /**
     * @param $relations
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * @param $table
     * @param $field
     * @param $value
     * @return $this
     */
    public function whereHas($table, $field, $value)
    {
        $this->field = $field;
        $this->value = $value;

        $this->model->whereHas($table, function ($item) {
            $item->where($this->field, $this->value);
        });

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset($offset = 0)
    {
        $this->model->offset($offset);

        return $this;
    }

    /**
     * @param int $take
     * @return $this
     */
    public function take($take = 15)
    {
        $this->model->take($take);

        return $this;
    }

    public function whereLike($field, $value)
    {
        $this->model = $this->model->where($field, 'like', '%'.$value.'%');

        return $this;
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function groupBy($column = '*')
    {
        $this->model = $this->model->groupBy($column);

        return $this;
    }

    public function firstOrCreate(array $data)
    {
        return $this->model->firstOrCreate($data);
    }

    public function firstOrNew(array $data)
    {
        return $this->model->firstOrNew($data);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $count = $this->model->count();

        $this->resetModel();

        return $count;
    }

    /**
     * Add sub select queries to count the relations.
     *
     * @param  mixed $relations
     * @return $this
     */
    public function withCount($relations)
    {
        $this->model = $this->model->withCount($relations);

        return $this;
    }
}
