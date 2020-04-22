<?php

namespace App\Repositories;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Get all the model records in the database.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Delete the specified model record from the database.
     *
     * @param int|array $id
     *
     * @return int
     * @throws Exception
     */
    public function deleteById($id);

    /**
     * @param $item
     * @param $column
     *
     * @return Model|null
     */
    public function findByColumn($column, $item): ?Model;

    /**
     * Get the specified model record from the database.
     *
     * @param $id
     *
     * @return Model
     */
    public function find($id): Model;

    /**
     * Get the first specified model record from the database.
     *
     * @return Model
     */
    public function first(): Model;

    /**
     * Get all the specified model records in the database.
     *
     * @return Collection
     */
    public function get(): Collection;

    /**
     * @param $data
     * @return Builder|Model
     */
    public function create($data);

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return BaseRepositoryInterface
     */
    public function limit($limit): BaseRepositoryInterface;

    /**
     * Create a new instance of the model's query builder.
     *
     * @return BaseRepositoryInterface
     */
    public function query(): BaseRepositoryInterface;

    /**
     * @return Builder
     */
    public function getQuery();


    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return BaseRepositoryInterface
     */
    public function orderBy($column, $direction = 'asc'): BaseRepositoryInterface;

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator;

    /**
     * @param array $columns
     *
     * @return BaseRepositoryInterface
     */
    public function select(array $columns = ['*']): BaseRepositoryInterface;

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return BaseRepositoryInterface
     */
    public function where($column, $value, $operator = '=');

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return BaseRepositoryInterface
     */
    public function whereIn($column, $values);

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return BaseRepositoryInterface
     */
    public function with($relations): BaseRepositoryInterface;
}
