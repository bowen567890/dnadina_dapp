<?php

namespace App\Admin\Repositories;

use App\Models\UsersLimitModel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersLimit extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
