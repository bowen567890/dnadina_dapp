<?php

namespace App\Admin\Repositories;

use App\Models\ForexProductLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ForexProductLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
