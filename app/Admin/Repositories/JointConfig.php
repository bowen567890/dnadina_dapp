<?php

namespace App\Admin\Repositories;

use App\Models\JointConfigModel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class JointConfig extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
