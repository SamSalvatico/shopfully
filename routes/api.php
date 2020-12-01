<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('tree_nodes', ['as' => 'tree_nodes.index', 'uses' => 'API\TreeNodeAPIController@index']);
$router->get('tree_nodes/children', ['as' => 'tree_nodes.children', 'uses' => 'API\TreeNodeAPIController@children']);
$router->get('tree_nodes/{id}', ['as' => 'tree_nodes.show', 'uses' => 'API\TreeNodeAPIController@show']);
$router->put('tree_nodes/{id}', ['as' => 'tree_nodes.update', 'uses' => 'API\TreeNodeAPIController@update']);
$router->patch('tree_nodes/{id}', ['as' => 'tree_nodes.patch', 'uses' => 'API\TreeNodeAPIController@update']);
$router->post('tree_nodes', ['as' => 'tree_nodes.store', 'uses' => 'API\TreeNodeAPIController@store']);
$router->delete('tree_nodes/{id}', ['as' => 'tree_nodes.delete', 'uses' => 'API\TreeNodeAPIController@destroy']);

$router->get('tree_node_names', ['as' => 'tree_node_names.index', 'uses' => 'API\TreeNodeNameAPIController@index']);
$router->get('tree_node_names/{id}', ['as' => 'tree_node_names.show', 'uses' => 'API\TreeNodeNameAPIController@show']);
$router->put('tree_node_names/{id}', ['as' => 'tree_node_names.update', 'uses' => 'API\TreeNodeNameAPIController@update']);
$router->patch('tree_node_names/{id}', ['as' => 'tree_node_names.patch', 'uses' => 'API\TreeNodeNameAPIController@update']);
$router->post('tree_node_names', ['as' => 'tree_node_names.store', 'uses' => 'API\TreeNodeNameAPIController@store']);
$router->delete('tree_node_names/{id}', ['as' => 'tree_node_names.delete', 'uses' => 'API\TreeNodeNameAPIController@destroy']);
