<?php

namespace App\Admin\Repositories;

use App\Models\UsersPowerConfig as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersPowerConfig extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
