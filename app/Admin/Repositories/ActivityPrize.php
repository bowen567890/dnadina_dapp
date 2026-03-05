<?php

namespace App\Admin\Repositories;

use App\Models\ActivityPrize as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ActivityPrize extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
