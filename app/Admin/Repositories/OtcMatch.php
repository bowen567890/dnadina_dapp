<?php

namespace App\Admin\Repositories;

use App\Models\OtcMatch as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class OtcMatch extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
