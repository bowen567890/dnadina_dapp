<?php

namespace App\Admin\Repositories;

use App\Models\UsersIncome as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersIncome extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
