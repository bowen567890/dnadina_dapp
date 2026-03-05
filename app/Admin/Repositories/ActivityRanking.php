<?php

namespace App\Admin\Repositories;

use App\Models\ActivityRanking as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ActivityRanking extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
