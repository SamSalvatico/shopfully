<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TreeNodeNameResource;
use App\Http\Resources\TreeNodeNameCollectionResource;
use App\Models\TreeNodeName;
use App\Repositories\TreeNodeName\TreeNodeNameContract;
use Illuminate\Http\Request;
use Response;

/**
 * @package App\Http\Controllers\API
 */
class TreeNodeNameAPIController extends APIBaseController
{
    /** @var  TreeNodeNameContract */
    private $treeNodeNameRepository;

    public function __construct(TreeNodeNameContract $treeNodeNameRepository)
    {
        $this->treeNodeNameRepository = $treeNodeNameRepository;
    }

/**
     *
     * @OA\Get(
     *     path="/api/v1/tree_node_names",
     *     operationId="getTreeNodeNames",
     *     tags={"TreeNodeNames"},
     *     summary="Get all TreeNodeNames",
     *     description="Return all TreeNodeNames that matches request",
     *    security={
     *       {"oauth2Auth": {"tree_node_names_view"}}
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
     *             @OA\Items(ref="#/components/schemas/TreeNodeName")
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
        $treeNodeNames = $this->treeNodeNameRepository->getByRequest($request);
        return $this->sendResponse(new TreeNodeNameCollectionResource(TreeNodeNameResource::collection($treeNodeNames)->resource));
    }

    /**
         * @OA\Post(
         *     path="/api/v1/tree_node_names",
         *     operationId="addTreeNodeName",
         *     tags={"TreeNodeNames"},
         *    security={
         *       {"oauth2Auth": {"tree_node_names_create"}}
         *     },
         *     summary="Add a new TreeNodeName",
         *     description="",
         *     @OA\RequestBody(
         *         description="TreeNodeName object that needs to be added to the store",
         *         required=true,
         *         @OA\JsonContent(ref="#/components/schemas/TreeNodeName"),
         *         @OA\MediaType(
         *             mediaType="application/xml",
         *             @OA\Schema(ref="#/components/schemas/TreeNodeName")
         *         ),
         *     ),
         *     @OA\RequestBody(
         *         description="TreeNodeName object that needs to be added to the store",
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="application/xml",
         *             @OA\Schema(ref="#/components/schemas/TreeNodeName")
         *         )
         *     ),
         *     @OA\Response(
         *         response=405,
         *         description="Invalid TreeNodeName",
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="successful operation",
         *          @OA\JsonContent(
         *             ref="#/components/schemas/TreeNodeName"
         *         ),
         *     ),
         * )
         */
    public function store(Request $request)
    {
        $this->validate($request,TreeNodeName::$rules);

        $input = $request->all();

        $treeNodeName = $this->treeNodeNameRepository->create($input);

        return $this->sendResponse(new TreeNodeNameResource($treeNodeName));
    }

/**
     * @OA\Get(
     *     path="/api/v1/tree_node_names/{id}",
     *     operationId="getTreeNodeName",
     *     tags={"TreeNodeNames"},
     *     summary="Find TreeNodeName by ID",
     *     description="Returns a single TreeNodeName",
     *    security={
     *       {"oauth2Auth": {"tree_node_names_view"}}
     *     },
     *     @OA\Parameter(
     *         description="ID of TreeNodeName to return",
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
     *         @OA\JsonContent(ref="#/components/schemas/TreeNodeName")
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
        /** @var TreeNodeName $treeNodeName */
        $treeNodeName = $this->treeNodeNameRepository->find($id);

        if (empty($treeNodeName)) {
            return $this->sendError('TreeNodeName not found');
        }

        return $this->sendResponse(new TreeNodeNameResource($treeNodeName));
    }

/**
     * @OA\Put(
     *     path="/api/v1/tree_node_names/{id}",
     *     operationId="updateTreeNodeName",
     *     tags={"TreeNodeNames"},
     *     summary="Update an existing TreeNodeName",
     *     description="",
     *    security={
     *       {"oauth2Auth": {"tree_node_names_edit"}}
     *     },
     *     @OA\Parameter(
     *         description="TreeNodeName id to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="TreeNodeName object that needs to be updated into the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TreeNodeName"),
     *         @OA\MediaType(
     *            mediaType="application/xml",
     *            @OA\Schema(ref="#/components/schemas/TreeNodeName")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TreeNodeName")
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
     *         description="Invalid TreeNodeName",
     *     )
     * )
     */
    public function update($id, Request $request)
    {
        $this->validate($request,TreeNodeName::$rules);

        $input = $request->all();

        /** @var TreeNodeName $treeNodeName */
        $treeNodeName = $this->treeNodeNameRepository->find($id);

        if (empty($treeNodeName)) {
            return $this->sendError('TreeNodeName not found');
        }

        $treeNodeName = $this->treeNodeNameRepository->update($input, $id);

        return $this->sendResponse(new TreeNodeNameResource($treeNodeName));
    }

/**
     * @OA\Delete(
     *     path="/api/v1/tree_node_names/{id}",
     *     operationId="deleteTreeNodeName",
     *     tags={"TreeNodeNames"},
     *     summary="Deletes an TreeNodeName",
     *     description="",
     *    security={
     *       {"oauth2Auth": {"tree_node_names_delete"}}
     *     },
     *     @OA\Parameter(
     *         description="TreeNodeName id to delete",
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
     *         description="TreeNodeName not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        /** @var TreeNodeName $treeNodeName */
        $treeNodeName = $this->treeNodeNameRepository->find($id);

        if (empty($treeNodeName)) {
            return $this->sendError('TreeNodeName not found');
        }

        $treeNodeName->delete();

        return $this->sendResponse(["id" => $id]);
    }

}
