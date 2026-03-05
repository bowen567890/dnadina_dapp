<?php

namespace App\Admin\Repositories;

use App\Models\TradeOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class TradeOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
