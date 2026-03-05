<?php

namespace App\Admin\Repositories;

use App\Models\VitasUser as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class VitasUser extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
