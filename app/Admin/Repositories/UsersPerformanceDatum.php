<?php

namespace App\Admin\Repositories;

use App\Models\UsersPerformanceDatum as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersPerformanceDatum extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
