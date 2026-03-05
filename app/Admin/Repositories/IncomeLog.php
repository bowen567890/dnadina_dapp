<?php

namespace App\Admin\Repositories;

use App\Models\IncomeLogModel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class IncomeLog extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
