<?php


namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function get($columns = ['*']);

    public function all($columns = ['*']);

    public function find($id, $columns = ['*']);

    public function findOrFail($id, $columns = ['*']);

    public function first($columns = ['*']);

    public function firstOrFail($field, $value, $columns = ['*']);

    public function onlyTrashed();

    public function withTrashed();

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id = 0);

    public function orderBy($orderBy = 'created_at', $sortBy = 'asc');

    public function paginate($perPage = null, $columns = ['*']);

    public function random();

    public function isExist($id);

    public function with($relations);

    public function updateOrCreate(array $attributes, array $values = []);

    public function groupBy($column);

    public function withCount($relations);
}
