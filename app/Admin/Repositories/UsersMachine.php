<?php

namespace App\Admin\Repositories;

use App\Models\UserMachine as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersMachine extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
