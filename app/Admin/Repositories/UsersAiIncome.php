<?php

namespace App\Admin\Repositories;

use App\Models\UsersAiIncome as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class UsersAiIncome extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
