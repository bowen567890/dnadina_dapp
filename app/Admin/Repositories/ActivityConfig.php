<?php

namespace App\Admin\Repositories;

use App\Models\ActivityConfig as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ActivityConfig extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
