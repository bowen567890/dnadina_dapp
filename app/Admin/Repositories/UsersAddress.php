<?php

namespace App\Admin\Repositories;

use App\Models\UsersAddress as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersAddress extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
