<?php

namespace App\Admin\Repositories;

use App\Models\UsersNewcomer as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersNewcomer extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
