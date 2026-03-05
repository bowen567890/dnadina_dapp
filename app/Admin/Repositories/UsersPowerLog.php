<?php

namespace App\Admin\Repositories;

use App\Models\UsersPowerLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersPowerLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
