<?php

namespace App\Admin\Repositories;

use App\Models\PledgeReleaseLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PledgeReleaseLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
