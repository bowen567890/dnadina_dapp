<?php

namespace App\Admin\Repositories;

use App\Models\ItnfVerification as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ItnfVerification extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
