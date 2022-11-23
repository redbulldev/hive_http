<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name = 'positions';
$exception_feature = true;

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
    throw new Exception('Data must be numeric!');
}

if (is_int($data->point)) {
    if ($data->point < 1) {
        throw new Exception('Do not enter leading zeros!!');
    }

    throwError($container, $request, [
        'point' => v::digit()->length(1, 6)->notEmpty()->noWhitespace(),
    ]);
}

if (is_float($data->point)) {
    $numlength = strlen((string) $data->point);

    if ($numlength < 0 || $numlength > 6) {
        throw new Exception('Invalid data, length number > 0 && <= 6!');
    }

    throwError($container, $request, [
        'point' => v::floatVal()->notEmpty()->noWhitespace(),
    ]);
}
// die('die');

$data->point = (string) $data->point;

if (!empty($data->point)) {
    $data->point = ltrim($data->point, 0);

    $count = strlen($data->point);

    $dot = strpos($data->point, '.');
    // echo $dot;die();

    $dots = substr_count($data->point, '.');

    if ($count != 1 && $count == $dot + 1 || $dot === 0  || $dots > 1) { //
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

    // echo $dot;die();
}

// die('ok');