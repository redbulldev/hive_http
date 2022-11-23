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
// if (is_string($data->point)) {
//     throw new Exception('Data must be numeric!');
// }

// if (is_int($data->point)) {
//     if ($data->point < 1) {
//         throw new Exception('Do not enter leading zeros!!');
//     }

//     throwError($container, $request, [
//         'point' => v::digit()->length(1, 6)->notEmpty()->noWhitespace(),
//     ]);
// }

// if (is_float($data->point)) {
//     $numlength = strlen((string) $data->point);

//     if ($numlength < 0 || $numlength > 6) {
//         throw new Exception('Invalid data, length number > 0 && <= 6!');
//     }

//     throwError($container, $request, [
//         'point' => v::floatVal()->notEmpty()->noWhitespace(),
//     ]);
// }

// $data->point = (string) $data->point;

// if (!empty($data->point)) {
//     $data->point = ltrim($data->point, 0);

//     $count = strlen($data->point);

//     $dot = strpos($data->point, '.');
//     // echo $dot;die();

//     $dots = substr_count($data->point, '.');

//     if ($count != 1 && $count == $dot + 1 || $dot === 0 || $dots > 1) { //
//         throw new Exception('Invalid data!');
//     }

//     if ($dot) {
//         $data->point = rtrim($data->point, 0);

//         $count = strlen($data->point);

//         $dot = strpos($data->point, '.');

//         if ($count == $dot + 1) {
//             $data->point = rtrim($data->point, '.');
//         }
//     }

//     // echo $dot;die();
// }

// die('ok');

$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$levels = [];

function checkPosition($value)
{
    $check = DB::table('positions')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getPosition($value)
{
    $position = DB::table('positions')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

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

// $data_point_positions = json_decode($request->getBody());
$data_point_positions = json_decode($request->getBody(), true);

foreach ($data_point_positions as $key => $title) {
    if ($key == getPosition($key)) {
        $position_id = checkPosition($key);

        foreach ($title as $index => $lable) {
            $format_level = substr($index, strpos($index, '.') + 1);

            $level_not_exist = substr($index, 0, strpos($index, '.')); 

            if ($format_level == getLevel($format_level) && $key == getPosition($key)) {
                $status = DB::table('level_positions')->where('id', substr($index, 0, strpos($index, '.')))->update([
                    'point' => $data_point_positions[$key][$index],
                ]);
            }

            if ($level_not_exist == 0) {
                $level_id = checkLevel($format_level);

                $check_exist = DB::table('level_positions')
                    ->whereIn('level_id',  [$level_id])->whereIn('position_id',  [$position_id])
                    ->first();

                if (empty($check_exist)) {
                      DB::table('level_positions')->updateOrInsert([
                        'level_id' => $level_id,
                        'position_id' => $position_id,
                        'point' => $data_point_positions[$key][$index],
                    ]);
                }              
            }
        }
    }
}

// print_r($test);

// die('end');
