<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id');
$obj->where('parent.isdelete', 0);

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


$moreselect = ['parent.title AS parent_title'];
