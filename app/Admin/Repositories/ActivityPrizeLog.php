<?php

namespace App\Admin\Repositories;

use App\Models\ActivityPrizeLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ActivityPrizeLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
