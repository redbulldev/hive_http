<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

// // $name='level_positions';
// $name='positions';
// throwError($container,$request, [
//     'title' => v::length(2, 200)->notEmpty()
// ]);
// echo 'ok';

// // throwError($container, $request, [
// //     'point' => v::length(1, 6)->notEmpty()->noWhitespace()
// // ]);

// die($data->point);

// // if (!empty($data->point)) {
// //     if (!preg_match("?<=^| )\d+(\.\d+)?(?=$|", $data->point)) {
// //         throw new Exception('Enter only numbers and periods (.)');
// //     }
// // }

// Giá trị mặc định bằng 1
// Hệ thống hiển thị input box để chỉnh sửa
// - required
// - maxlength=6
// - Chỉ nhập chữ số và dấu chấm (.)
// - Tối đa 4 chữ số sau dấu chấm
// - Tự cắt số 0 đầu/ cuối(sau dấu chấm) sau khi lưu
// - trim() khoảng trắng
// Nút xác nhận chỉ Lưu khi có chỉnh sửa

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
if (is_string($data->point)) {
    throw new Exception('Invalid data!');
}

if (is_int($data->point)) {
    if ($data->point < 1) {
        throw new Exception('Invalid data!');
    }

    throwError($container, $request, [
        'point' => v::digit()->length(1, 6)->notEmpty()->noWhitespace(),
    ]);
}

if (is_float($data->point)) {
    $numlength = strlen((string) $data->point);

    if ($numlength < 0 && $numlength > 6) {
        throw new Exception('Invalid data!');
    }

    throwError($container, $request, [
        'point' => v::floatVal()->notEmpty()->noWhitespace(),
    ]);
}
die('die');

$data->point = (string) $data->point;

if (!empty($data->point)) {
    $data->point = ltrim($data->point, 0);

    $count = strlen($data->point);

    $dot = strpos($data->point, '.');
    // echo $dot;die();

    $dots = substr_count($data->point, '.');

    if ($count == $dot + 1 || $dot === 0 || $dots > 1) {
        throw new Exception('Invalid data!');
    }

    if ($dot) {
        $data->point = rtrim($data->point, 0);

        $count = strlen($data->point);

        $dot = strpos($data->point, '.');

        if ($count == $dot + 1) {
            $data->point = rtrim($data->point, '.');
        }
    }

    echo $dot;die();
}

die('ok');
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
