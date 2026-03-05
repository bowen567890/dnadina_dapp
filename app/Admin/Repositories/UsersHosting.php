<?php

namespace App\Admin\Repositories;

use App\Models\UsersHosting as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersHosting extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
