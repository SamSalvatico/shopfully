<?php

namespace App\Repositories\TreeNode;

use App\Exceptions\GenericException;
use App\Models\TreeNode;
use App\Repositories\RepositoryContract;
use Illuminate\Http\Request;

interface TreeNodeContract extends RepositoryContract
{
    public function children(Request $request);
}
