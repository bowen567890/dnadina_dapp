<?php

namespace App\Admin\Repositories;

use App\Models\MachineLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class MachineLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
