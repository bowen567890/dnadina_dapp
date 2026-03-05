<?php

namespace App\Admin\Repositories;

use App\Models\UserMachine as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserMachine extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
