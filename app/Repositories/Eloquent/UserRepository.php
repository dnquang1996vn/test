<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * @method User first()
 * @method User find($id)
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var Model|User
     */
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getPaginated($limit = 10, $orderBy = 'created_at', $sort = 'desc'): LengthAwarePaginator
    {
        return $this->orderBy($orderBy, $sort)
            ->paginate($limit);
    }
}
