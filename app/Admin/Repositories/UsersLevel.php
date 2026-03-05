<?php

namespace App\Admin\Repositories;

use App\Models\UsersLevel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersLevel extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
