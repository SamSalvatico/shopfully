<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TreeNodeResource;
use App\Http\Resources\TreeNodeCollectionResource;
use App\Models\TreeNode;
use App\Repositories\TreeNode\TreeNodeContract;
use Illuminate\Http\Request;
use Response;

/**
 * @package App\Http\Controllers\API
 */
class TreeNodeAPIController extends APIBaseController
{
    /** @var  TreeNodeContract */
    private $treeNodeRepository;

    public function __construct(TreeNodeContract $treeNodeRepository)
    {
        $this->treeNodeRepository = $treeNodeRepository;
    }

/**
     *
     * @OA\Get(
     *     path="/api/v1/tree_nodes",
     *     operationId="getTreeNodes",
     *     tags={"TreeNodes"},
     *     summary="Get all TreeNodes",
     *     description="Return all TreeNodes that matches request",
     *    security={
     *       {"oauth2Auth": {"tree_nodes_view"}}
     *     },
     *     @OA\Parameter(
     *         name="criteria",
     *         in="query",
     *         description="Some optional other parameter",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TreeNode")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $treeNodes = $this->treeNodeRepository->getByRequest($request);
        return $this->sendResponse(new TreeNodeCollectionResource(TreeNodeResource::collection($treeNodes)->resource));
    }

    /**
         * @OA\Post(
         *     path="/api/v1/tree_nodes",
         *     operationId="addTreeNode",
         *     tags={"TreeNodes"},
         *    security={
         *       {"oauth2Auth": {"tree_nodes_create"}}
         *     },
         *     summary="Add a new TreeNode",
         *     description="",
         *     @OA\RequestBody(
         *         description="TreeNode object that needs to be added to the store",
         *         required=true,
         *         @OA\JsonContent(ref="#/components/schemas/TreeNode"),
         *         @OA\MediaType(
         *             mediaType="application/xml",
         *             @OA\Schema(ref="#/components/schemas/TreeNode")
         *         ),
         *     ),
         *     @OA\RequestBody(
         *         description="TreeNode object that needs to be added to the store",
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="application/xml",
         *             @OA\Schema(ref="#/components/schemas/TreeNode")
         *         )
         *     ),
         *     @OA\Response(
         *         response=405,
         *         description="Invalid TreeNode",
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="successful operation",
         *          @OA\JsonContent(
         *             ref="#/components/schemas/TreeNode"
         *         ),
         *     ),
         * )
         */
    public function store(Request $request)
    {
        $this->validate($request,TreeNode::$rules);

        $input = $request->all();

        $treeNode = $this->treeNodeRepository->create($input);

        return $this->sendResponse(new TreeNodeResource($treeNode));
    }

/**
     * @OA\Get(
     *     path="/api/v1/tree_nodes/{id}",
     *     operationId="getTreeNode",
     *     tags={"TreeNodes"},
     *     summary="Find TreeNode by ID",
     *     description="Returns a single TreeNode",
     *    security={
     *       {"oauth2Auth": {"tree_nodes_view"}}
     *     },
     *     @OA\Parameter(
     *         description="ID of TreeNode to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TreeNode")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Item not found"
     *     )
     * )
     */
    public function show($id)
    {
        /** @var TreeNode $treeNode */
        $treeNode = $this->treeNodeRepository->find($id);

        if (empty($treeNode)) {
            return $this->sendError('TreeNode not found');
        }

        return $this->sendResponse(new TreeNodeResource($treeNode));
    }

/**
     * @OA\Put(
     *     path="/api/v1/tree_nodes/{id}",
     *     operationId="updateTreeNode",
     *     tags={"TreeNodes"},
     *     summary="Update an existing TreeNode",
     *     description="",
     *    security={
     *       {"oauth2Auth": {"tree_nodes_edit"}}
     *     },
     *     @OA\Parameter(
     *         description="TreeNode id to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="TreeNode object that needs to be updated into the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TreeNode"),
     *         @OA\MediaType(
     *            mediaType="application/xml",
     *            @OA\Schema(ref="#/components/schemas/TreeNode")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TreeNode")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid TreeNode",
     *     )
     * )
     */
    public function update($id, Request $request)
    {
        $this->validate($request,TreeNode::$rules);

        $input = $request->all();

        /** @var TreeNode $treeNode */
        $treeNode = $this->treeNodeRepository->find($id);

        if (empty($treeNode)) {
            return $this->sendError('TreeNode not found');
        }

        $treeNode = $this->treeNodeRepository->update($input, $id);

        return $this->sendResponse(new TreeNodeResource($treeNode));
    }

/**
     * @OA\Delete(
     *     path="/api/v1/tree_nodes/{id}",
     *     operationId="deleteTreeNode",
     *     tags={"TreeNodes"},
     *     summary="Deletes an TreeNode",
     *     description="",
     *    security={
     *       {"oauth2Auth": {"tree_nodes_delete"}}
     *     },
     *     @OA\Parameter(
     *         description="TreeNode id to delete",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TreeNode not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        /** @var TreeNode $treeNode */
        $treeNode = $this->treeNodeRepository->find($id);

        if (empty($treeNode)) {
            return $this->sendError('TreeNode not found');
        }

        $treeNode->delete();

        return $this->sendResponse(["id" => $id]);
    }

    /**
     *
     * @OA\Get(
     *     path="/api/v1/tree_nodes/children",
     *     operationId="getTreeNodeChildren",
     *     tags={"TreeNodes"},
     *     summary="Get children TreeNodes",
     *     description="Return all children TreeNodes that matches request",
     *    security={
     *       {"oauth2Auth": {"tree_nodes_view"}}
     *     },
     *     @OA\Parameter(
     *         name="node_id",
     *         in="query",
     *         description="the node id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="Language",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page_num",
     *         in="query",
     *         description="Some optional other parameter",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *    @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         description="Page size",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *    @OA\Parameter(
     *         name="search_keyword",
     *         in="query",
     *         description="Search for",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *         name="only_direct",
     *         in="query",
     *         description="My personal custom parameter, if true returns count about only children where level = requestedNode->level+1. Default is true",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *          @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TreeNode")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function children(Request $request)
    {
        $treeNodes = $this->treeNodeRepository->children($request);
        return $this->sendResponse(new TreeNodeCollectionResource(TreeNodeResource::collection($treeNodes)->resource));
    }
}
