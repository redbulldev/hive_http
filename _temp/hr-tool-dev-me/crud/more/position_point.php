<?php

use Illuminate\Database\Capsule\Manager as DB;

error_reporting (E_ALL ^ E_NOTICE);

$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$lables = [];

$levels = [];

// ----------------------------
$obj->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id');

// $obj->leftJoin('parent', 'parent.id', '=', 'level_positions.position_id');

$obj->where(['parent.isdelete' => 0, 'positions.isdelete' => 0]);

// if(empty($permission->positions->all))
// {
//     $obj->join('positions_requester','positions_requester.position_id', '=', 'positions.id');
//     $obj->where('positions_requester.user_id',$user->username);
// }else if(!empty($requestor))
// {
//     $obj->join('positions_requester','positions_requester.position_id', '=', 'positions.id');
//     $obj->whereIn('positions_requester.user_id',$requestor);
// }

// ---------------------------------
$get_positions = $obj->get();
// $datas = [];

// foreach($obj->get() as $key => $value){
//     $datas[$value->id] = $value->title;
//     echo $value->title.';'; 
// }
// // echo count($datas);
// die($response->withJson($get_positions));

// die($response->withJson($get_positions));


function checkPosition($value)
{
    // $check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1,'isdelete' => 0])->where('parent_id', '!=', 0)->first();
 
// echo 'csdcvd';
    // if (!empty($check)) {
    //     return $check->id;
    // }
    // $datas = json_decode($get_positions, true);
    // $datas = (array)json_decode($get_positions);

   $datas = DB::table('positions')->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id');
// print_r($datas);
// return $datas->get();

//    die();


//    $datas = json_decode($datas, true);
    foreach ($datas->get() as $key => $v) {
        // echo $v->id.';';
       if ($v->id == $value) {
        // die('ok');
            return $v->id;
       }
    }

    return false;
}

// $test = checkPosition(681);
// // die($response->withJson($test));

// echo $test;
// // print_r($test);
// die();

function getPosition($value)
{
    // $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    // if (!empty($position)) {
    //     return $position->title;
    // }

    $datas = DB::table('positions')->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id');

    foreach ($datas->get() as $v) {
        if ($v->id == $value) {
             return $v->title;
        }
     }

    return false;
}

function checkLevel($value)
{
    $check = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getLevel($value)
{
    $level = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($level)) {
        return $level->title;
    }

    return false;
}

function checkLevelPosition($value)
{
    $check = DB::table('level_positions')->where('position_id', $value)->where(['isdelete' => 0])->where('position_id', '!=', 0)->first();

    if (!empty($check)) {
        return $check->position_id;
    }

    return false;
}

foreach ($get_positions as $key => $value) {
    $lables[$value->id] = $value->title;
}

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}

$point_positions = [[[]]];

for ($i = 0; $i < count($level_positions); $i++) {
    if ($level_positions[$i]->level_id == checkLevel($level_positions[$i]->level_id) && $level_positions[$i]->position_id == checkPosition($level_positions[$i]->position_id)) {
        // die('ok');
        $point_positions[getPosition($level_positions[$i]->position_id)][getLevel($level_positions[$i]->level_id)] = $level_positions[$i]->point;
    }
}

foreach ($lables as $key => $lable) {
    if (empty($point_positions[$lable])) {
        foreach ($levels as $index => $level) {
            if (empty($point_positions[$lable][$level])) {
                $point_positions[$lable][$level] = 1;
            }
        }
    }

    if (!empty($point_positions[$lable])) {
        foreach ($levels as $index => $level) {
            if (empty($point_positions[$lable][$level])) {
                $point_positions[$lable][$level] = 1;
            }
        }
    }
}

unset($point_positions[0]);

// $datas = DB::table('positions')->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->get();
// die($response->withJson($datas));

$results = [
    'status' => 'success',
    'data' => $point_positions ? $point_positions : null,
    'total' => $point_positions ? count($point_positions) : null,
    'time' => time(),
];
