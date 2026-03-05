<?php

namespace App\Admin\Repositories;

use App\Models\UsersHostingOperatorLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersHostingOperatorLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
