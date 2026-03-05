<?php

namespace App\Admin\Repositories;

use App\Models\UsersShrink as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersShrink extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
