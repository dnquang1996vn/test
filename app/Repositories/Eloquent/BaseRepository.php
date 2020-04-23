<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository model.
     *
     * @var Model
     */
    protected $model;

    /**
     * The query builder.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Selected column list
     *
     * @var array|null
     */
    protected $selectedColumns;

    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected $take;

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected $scopes = [];

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function count(): int
    {
        return $this->get()->count();
    }

    public function deleteById($id)
    {
        return $this->model->destroy($id);
    }

    public function findByColumn($column, $item): ?Model
    {
        $this->unsetSelect()->unsetClauses();

        $this->query()->eagerLoad()->setSelect();

        return $this->query->where($column, $item)->first();
    }

    public function find($id): Model
    {
        $this->unsetSelect()->unsetClauses();

        $this->query()->eagerLoad()->setSelect();

        return $this->query->findOrFail($id);
    }


    public function create($data)
    {
        return $this->getQuery()->create($data);
    }

    public function first(): Model
    {
        $this->query()->eagerLoad()->setSelect()->setClauses()->setScopes();

        $model = $this->query->firstOrFail();

        $this->unsetClauses();

        return $model;
    }

    public function get(): Collection
    {
        $this->query()->eagerLoad()->setSelect()->setClauses()->setScopes();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    public function limit($limit): BaseRepositoryInterface
    {
        $this->take = $limit;

        return $this;
    }

    public function orderBy($column, $direction = 'asc'): BaseRepositoryInterface
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $this->query()->eagerLoad()->setSelect()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    public function select(array $columns = ['*']): BaseRepositoryInterface
    {
        $this->selectedColumns = $columns;
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    public function with($relations): BaseRepositoryInterface
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->relations = $relations;

        return $this;
    }

    public function query(): BaseRepositoryInterface
    {
        $this->query = $this->model->query();

        return $this;
    }

    public function getQuery()
    {
        return $this->model->query();
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return BaseRepository
     */
    protected function eagerLoad(): self
    {
        $this->query->with($this->relations);

        return $this;
    }

    /**
     * Set selected columns to the query builder
     *
     * @return BaseRepository
     */
    protected function setSelect(): self
    {
        if ($this->selectedColumns) {
            $this->query->select($this->selectedColumns);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return BaseRepository
     */
    protected function setClauses(): self
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return BaseRepository
     */
    protected function setScopes(): self
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the selected column list
     *
     * @return BaseRepository
     */
    protected function unsetSelect(): self
    {
        $this->selectedColumns = null;

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return BaseRepository
     */
    protected function unsetClauses(): self
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }
}
