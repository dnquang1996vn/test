<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function __construct(User $user);

    /**
     * @param  int  $limit
     * @param  string  $orderBy
     * @param  string  $sort
     *
     * @return LengthAwarePaginator
     */
    public function getPaginated($limit = 10, $orderBy = 'created_at', $sort = 'desc'): LengthAwarePaginator;

}
