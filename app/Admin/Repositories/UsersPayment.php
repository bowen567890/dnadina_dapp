<?php

namespace App\Admin\Repositories;

use App\Models\UsersPayment as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersPayment extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
