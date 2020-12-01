<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(
 * @OA\Xml(name="TreeNodeName"), 
 * @OA\Property(property="id", description="id", type="integer", nullable="false"),
 * @OA\Property(property="tree_node_id", description="tree_node_id", type="integer", nullable="false"),
 * @OA\Property(property="language", description="language", type="string", nullable="false"),
 * @OA\Property(property="node_name", description="node_name", type="string", nullable="false"),
 * required={"tree_node_id","language","node_name"}
 * )
 */
class TreeNodeName extends BaseModel
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var string
     */
    protected $table = 'tree_node_names';

    /**
     * @var array
     */
    public $blockingRelations = [
		
	];

    /**
     * @var array
     */
    public $relationsToDelete = [
		
	];

    /**
     * @var array
     */
    protected $touches = [
		
	];

    /**
     * @var array
     */
    protected $fillable = [
		'tree_node_id', 
		'language', 
		'node_name'
	];

    /**
     * @var array
     */
    protected $casts = [
		'id' => 'integer',
		'tree_node_id' => 'integer',
		'language' => 'string',
		'node_name' => 'string',
	];

    /**
     * @var array
     */
    public static $rules = [
		'tree_node_id' => 'required|integer',
		'language' => 'required|max:255',
		'node_name' => 'required|max:255',
	];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function treeNode()
    {
        return $this->belongsTo('App\Models\TreeNode', null, 'node_id');
    }
}
