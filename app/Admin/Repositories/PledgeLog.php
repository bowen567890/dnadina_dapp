<?php

namespace App\Admin\Repositories;

use App\Models\PledgeLog as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PledgeLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
