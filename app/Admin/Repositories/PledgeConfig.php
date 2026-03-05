<?php

namespace App\Admin\Repositories;

use App\Models\PledgeConfig as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class PledgeConfig extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
