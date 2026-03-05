<?php

namespace App\Admin\Repositories;

use App\Models\UserLimitModel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UserLimit extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
