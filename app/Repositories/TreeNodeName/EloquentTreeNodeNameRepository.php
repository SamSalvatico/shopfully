<?php

namespace App\Repositories\TreeNodeName;

use App\Exceptions\GenericException;
use App\Models\TreeNodeName;
use App\Repositories\EloquentRepository;

class EloquentTreeNodeNameRepository extends EloquentRepository implements TreeNodeNameContract
{

    /**
     * @var TreeNodeName
     */
    protected $model;

    public function __construct(
        TreeNodeName $model
    )
    {
        $this->model = $model;
    }

}
