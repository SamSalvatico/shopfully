<?php

namespace App\Repositories\TreeNode;

use App\Models\TreeNode;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function collect;
use function in_array;
use function key_exists;

class EloquentTreeNodeRepository extends EloquentRepository implements TreeNodeContract
{

    /**
     * @var TreeNode
     */
    protected $model;

    public function __construct(
        TreeNode $model
    ) {
        $this->model = $model;
    }

    /**
     * Get the children response
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function children(Request $request)
    {
        $requestValues = $request->all();
        $language = null;
        $pageNum = 0;
        $pageSize = 100;
        $searchKeyword = null;
        $onlyDirectChildrenCount = false;
        /** @var TreeNode $node */
        $node = $this->validateChildrenInputAndGetCurrentNode(
            $requestValues,
            $language,
            $pageNum,
            $pageSize,
            $searchKeyword,
            $onlyDirectChildrenCount
        );
        $children = $this->buildAndRunGetChildrenQuery(
            $node,
            $language,
            $searchKeyword,
            $pageNum,
            $pageSize,
            $onlyDirectChildrenCount
        );
        $allNodesList = $this->newQuery()->with('treeNodeNames')->get();
        return $this->getOutputChildrenList($children, $language, $allNodesList, $onlyDirectChildrenCount);
    }

    /**
     * Process the output counting the children for a certain node
     *
     * @param $children
     * @param $language
     * @param $fullNodeList
     * @param $onlyDirectChildrenCount
     * @return \Illuminate\Support\Collection
     */
    private function getOutputChildrenList($children, $language, $fullNodeList, $onlyDirectChildrenCount)
    {
        $outputCollection = collect([]);
        foreach ($children as $currentChild) {
            if ($onlyDirectChildrenCount) {
                $allChildrenNodes = $fullNodeList
                    ->where('i_left', '>', $currentChild->i_left)
                    ->where('i_right', '<', $currentChild->i_right);
            } else {
                $allChildrenNodes = $fullNodeList
                    ->where('i_left', '>', $currentChild->i_left)
                    ->where('i_right', '<', $currentChild->i_right)
                    ->where('level', '=', $currentChild->level + 1);
            }
            $outputCollection->push([
                "children_count" => $allChildrenNodes->count(),
                "name" => $currentChild->treeNodeNames->where('language', $language)->first()->node_name,
                "node_id" => $currentChild->node_id
            ]);
        }
        return $outputCollection;
    }

    /**
     * Look for all the children of a certain node on db that match the parameters
     *
     * @param TreeNode $node
     * @param $language
     * @param $searchKeyword
     * @param $pageNum
     * @param $pageSize
     * @param $onlyDirectChildren
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    private function buildAndRunGetChildrenQuery(
        TreeNode $node,
        $language,
        $searchKeyword,
        $pageNum,
        $pageSize,
        $onlyDirectChildren
    ) {
        $childrenQuery = $this->newQuery()
            ->where('i_left', '>', $node->i_left)
            ->where('i_right', '<', $node->i_right);
        if ($onlyDirectChildren) {
            $childrenQuery = $childrenQuery->where('level', '=', $node->level + 1);
        }
        if (!empty($searchKeyword)) {
            $childrenQuery = $childrenQuery->join(
                'tree_node_names',
                'tree_node_names.tree_node_id',
                '=',
                'tree_nodes.node_id'
            )
                ->where('language', $language)
                ->where('node_name', self::getLikeOperator(), '%' . $searchKeyword . '%');
        }
        $childrenQuery = $childrenQuery->with('treeNodeNames');
        $childrenQuery = $childrenQuery->orderBy('node_id');
        return $childrenQuery->skip($pageSize * $pageNum)->limit($pageSize)->get();
    }

    /**
     * @param array $requestValues
     * @param $language
     * @param $pageNum
     * @param $pageSize
     * @param $searchKeyword
     * @param $onlyDirectChildren
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    private function validateChildrenInputAndGetCurrentNode(
        array $requestValues,
        &$language,
        &$pageNum,
        &$pageSize,
        &$searchKeyword,
        &$onlyDirectChildren
    ) {
        // We could use the Lumen validation but we need to set custom error messages
        $nodeId = isset($requestValues['node_id']) ? $requestValues['node_id'] : null;
        $node = empty($nodeId) ? null : $this->newQuery()->where('node_id', $nodeId)->first();
        if (empty($node)) {
            throw new BadRequestHttpException("Invalid node id");
        }
        if (!isset($requestValues['language'])) {
            throw new BadRequestHttpException("Missing mandatory params");
        } elseif (!in_array($requestValues['language'], ['english', 'italian'])) {
            throw new BadRequestHttpException("Language is not valid");
        }
        $language = $requestValues['language'];
        if (key_exists('page_num', $requestValues)) {
            $intValue = ctype_digit($requestValues['page_num']) ? intval($requestValues['page_num']) : null;
            if ($intValue === null || $intValue < 0) {
                throw new BadRequestHttpException("Invalid page number requested");
            } else {
                $pageNum = $intValue;
            }
        }
        if (key_exists('page_size', $requestValues)) {
            $intValue = ctype_digit($requestValues['page_size']) ? intval($requestValues['page_size']) : null;
            if ($intValue === null || $intValue < 0 || $intValue > 1000) {
                throw new BadRequestHttpException("Invalid page size requested");
            } else {
                $pageSize = $intValue;
            }
        }
        $searchKeyword = isset($requestValues['search_keyword']) ? $requestValues['search_keyword'] : null;
        $onlyDirectChildren = key_exists('only_direct', $requestValues)
            ? filter_var($requestValues['only_direct'], FILTER_VALIDATE_BOOLEAN) : false;
        $onlyDirectChildren = $onlyDirectChildren === null ? true : $onlyDirectChildren;
        return $node;
    }

    /**
     * check which is the used db and returns the like operator
     * @return string
     */
    private static function getLikeOperator()
    {
        return (config('database.default') === 'pgsql' ? "ilike" : "like");
    }
}
