<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id');
$obj->where('parent.isdelete', 0);

// $obj->leftJoin('level_positions', 'level_positions.position_id', '=', 'parent.id');
// $obj->where('level_positions.isdelete', 0);

// $obj->leftJoin('level', 'level.id', '=', 'level_positions.level_id');
// $obj->where('level.isdelete', 0);

// die($response->withJson($obj->get()));

if(!empty($params['isposition']))
{
    $obj->where('positions.parent_id','>',0);
}

if(empty($permission->positions->all))
{
    $obj->join('positions_requester','positions_requester.position_id', '=', 'positions.id');
    $obj->where('positions_requester.user_id',$user->username);
}else if(!empty($requestor))
{
    $obj->join('positions_requester','positions_requester.position_id', '=', 'positions.id');
    $obj->whereIn('positions_requester.user_id',$requestor);
}

$moreselect = ['parent.title AS parent_title'];  //, 'level.title as level_title']
