<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$levels = [];

function checkPosition($value)
{
    $check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
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

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}


            $datas = json_decode($request->getBody());

   foreach ($datas as $item => $data) {
        // print_r($item); //status data end
        foreach ($data as $key => $title) {
            // print_r($key); //test 7x
            foreach ($title as $index => $lable) {
                print_r($lable); //2 1 1 1..
            } 
        }  
    }         

// die($response->withJson($datas['data']));
die('end');
// // *** update - quy đổi điểm
// // 1. check position
// // 2. check level
// // 3. -> true ===> inserOrUpdate
// // note: $point_positions là data trả về từ frontend
// foreach($point_positions as $key => $title)
// {
//     if($key == getPosition($key)){
//         foreach($title as $index => $lable)
//         {
//             // print_r($lable);die();
//             if($index == getLevel($index)){
//                 DB::table('level_positions')->where('id', checkLevel($index))->where('id', checkPosition($key))->update([
//                     'point' => $point_positions[$title][$index][$lable];
//                 ]);
//             }
//         }
//     } else {
//         if (!empty(checkPosition($key))) {
//             $position_id = checkPosition($key);
//             foreach($levels as $index => $level){
//                 DB::table('level_positions')->insert([
//                     'level_id' => $index,
//                     'position_id' => $position_id,
//                     'point' => $point_positions[$title][$index][$lable];
//                 ]);
//             }
//         }
//     }
// }
