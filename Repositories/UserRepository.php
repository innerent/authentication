<?php

namespace Innerent\Authentication\Repositories;

use Innerent\Foundation\Repositories\Repository;
use Innerent\Authentication\Contracts\User as UserRepoContract;
use Innerent\Authentication\Models\User;

class UserRepository extends Repository implements UserRepoContract
{
    function __construct(User $model)
    {
        parent::__construct($model);
    }
}
