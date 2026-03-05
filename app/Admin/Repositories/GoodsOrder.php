<?php

namespace App\Admin\Repositories;

use App\Models\GoodsOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class GoodsOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
