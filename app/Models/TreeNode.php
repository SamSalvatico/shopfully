<?php

namespace App\Models;

/**
 * @OA\Schema(
 * @OA\Xml(name="TreeNode"),
 * @OA\Property(property="id", description="id", type="integer", nullable="false"),
 * @OA\Property(property="node_id", description="node_id", type="integer", nullable="false"),
 * @OA\Property(property="level", description="level", type="integer", nullable="false"),
 * @OA\Property(property="i_left", description="i_left", type="integer", nullable="false"),
 * @OA\Property(property="i_right", description="i_right", type="integer", nullable="false"),
 * required={"node_id","level","i_left","i_right"}
 * )
 */
class TreeNode extends BaseModel
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
    protected $table = 'tree_nodes';

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
		'node_id',
		'level',
		'i_left',
		'i_right'
	];

    /**
     * @var array
     */
    protected $casts = [
		'id' => 'integer',
		'node_id' => 'integer',
		'level' => 'integer',
		'i_left' => 'integer',
		'i_right' => 'integer',
	];

    /**
     * @var array
     */
    public static $rules = [
		'node_id' => 'required|integer',
		'level' => 'required|integer',
		'i_left' => 'required|integer',
		'i_right' => 'required|integer',
	];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treeNodeNames()
    {
        return $this->hasMany('App\Models\TreeNodeName', null, 'node_id');
    }
}
