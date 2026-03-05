<?php

namespace App\Admin\Repositories;

use App\Models\MachineCode as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MachineCode extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
