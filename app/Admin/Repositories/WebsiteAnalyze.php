<?php

namespace App\Admin\Repositories;

use App\Models\WebsiteAnalyze as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class WebsiteAnalyze extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
