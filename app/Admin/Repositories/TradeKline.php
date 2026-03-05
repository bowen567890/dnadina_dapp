<?php

namespace App\Admin\Repositories;

use App\Models\TradeKline as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class TradeKline extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
