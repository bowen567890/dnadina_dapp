<?php

namespace App\Admin\Repositories;

use App\Models\ForexProduct as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ForexProduct extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
