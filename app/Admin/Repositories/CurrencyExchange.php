<?php

namespace App\Admin\Repositories;

use App\Models\CurrencyExchange as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class CurrencyExchange extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
