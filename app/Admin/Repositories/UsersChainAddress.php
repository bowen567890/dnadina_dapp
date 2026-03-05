<?php

namespace App\Admin\Repositories;

use App\Models\UsersChainAddress as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersChainAddress extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
