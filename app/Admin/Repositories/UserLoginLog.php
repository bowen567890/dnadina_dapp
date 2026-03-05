<?php

namespace App\Admin\Repositories;

use App\Models\UserLoginLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserLoginLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
