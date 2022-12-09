<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name = 'level_positions';

$exception_feature = true;

// Giá trị mặc định bằng 1
// Hệ thống hiển thị input box để chỉnh sửa
// - required
// - maxlength=6
// - Chỉ nhập chữ số và dấu chấm (.)
// - Tối đa 4 chữ số sau dấu chấm
// - Tự cắt số 0 đầu/ cuối(sau dấu chấm) sau khi lưu
// - trim() khoảng trắng
// Nút xác nhận chỉ Lưu khi có chỉnh sửa

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// validaqte

$data_validates = json_decode($request->getBody());

// foreach ($data_validates as $key => $title) {
//     // print_r($key); //test 7x
//     foreach ($title as $index => $lable) {
//         print_r($lable); //2 1 1 1..
//     }
// }


// $s =  "3434.0";
// $dot = strpos($s, '.'); //return position
// echo $dot;die();
// // strlen($s);
// echo strlen($s);die();

foreach ($data_validates as $key => $title) {
    foreach ($title as $index => $point) {
            // echo $point.';';

        if (is_string($point)) {
            throw new Exception('Data must be numeric!');
        }

        if (is_int($point)  || is_float($point)) {
            $point = rtrim($point, 0);

            // if ($point < 1) {
            //     throw new Exception('Do not enter leading zeros!!');
            // }

            $numlength = strlen((string) $point);

            if ($numlength < 0 || $numlength > 6) {
                throw new Exception('Invalid data, length number > 0 && <= 6!');
            }
        }

        // die('cc');
        if (!empty($point)) {
            $point = (string) $point;

            $point = ltrim($point, 0);  //cut - 0 - left

            $count = strlen($point); //length
            // die($count);

            $dot = strpos($point, '.'); //return position; 0.1
            // echo $dot;die();

            $dots = substr_count($point, '.');  //count dot(.)

            // input: 2.
            if (($count != 1 && $count == $dot + 1) || $dots > 1 || $count > 6) { //
                throw new Exception('Invalid data!');
            }

            // if ($dot) {
            //     $point = rtrim($point, 0);  //cut - 0 - right

            //     $count = strlen($point);

            //     $dot = strpos($point, '.');

            //     // xoa (.) o cuoi khi remove (0) truoc 
            //     if ($count == $dot + 1) {
            //         $point = rtrim($point, '.');
            //     }              
            // }
        }
    }
}

// die('validate');

// die('ok1');









// save level_position //
$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

// $level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$levels = [];

function getObjPosition()
{
    return DB::table('positions')->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
        ->where(['positions.status' => 1, 'positions.point_status' => 1, 'positions.isdelete' => 0, 'parent.isdelete' => 0])
        ->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id')->get();
}

// $datas = getObjPosition();
// die($response->withJson($test));
// die('ok');

// foreach ($datas as $value) {
//             echo $value->title.';';
//     }
// die('okx');



function checkPosition($position)
{
    $check_positions = getObjPosition();

    foreach ($check_positions as $key => $value) {
        if ($value->title == $position) {
             return $value->id;
        }
    }

    return false;
}

function getPosition($position)
{
    $get_positions = getObjPosition();

    foreach ($get_positions as $value) {
        if ($value->title == $position) {
            return $value->title;
        }
    }

    return false;
}
// $te =  getPosition(123);
// die($response->withJson($te));

// die();


function checkLevel($value)
{
    $check = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getLevel($value)
{
    $level = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($level)) {
        return $level->title;
    }

    return false;
}

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}

$data_point_positions = json_decode($request->getBody(), true);

foreach ($data_point_positions as $key => $items) {
    if ($key == getPosition($key)) {
        $position_id = checkPosition($key);

        foreach ($items as $index => $lable) {
            if ($index == getLevel($index) && $key == getPosition($key)) {
                DB::table('level_positions')
                    ->whereIn('level_id', [checkLevel($index)])->whereIn('position_id',  [checkPosition($key)])
                    ->update([
                        'point' => $data_point_positions[$key][$index]
                    ]);
            } 

            // $level_id = checkLevel($index);

            // $check_exist = DB::table('level_positions')
            //     ->whereIn('level_id', [$level_id])->whereIn('position_id',  [$position_id])
            //     ->first();

            // if (empty($check_exist) && !empty($level_id)) {
            //     DB::table('level_positions')->updateOrInsert([
            //         'level_id' => $level_id,
            //         'position_id' => $position_id,
            //         'point' => rtrim($data_point_positions[$key][$index], 0),
            //     ]);
            // }              
        }
    }
}

// print_r($test);

// die('end');
// các trường họp test 
// +