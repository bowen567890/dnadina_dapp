<?php

namespace App\Admin\Repositories;

use App\Models\UsersPendingEarning as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersPendingEarning extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
