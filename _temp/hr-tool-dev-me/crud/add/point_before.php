<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name='level_positions';
// $name='positions';

// echo 'ok';

// throwError($container, $request, [
//     'point' => v::length(1, 6)->notEmpty()->noWhitespace()
// ]);


if (!empty($data->point)) {
    if (preg_match("^(\d)*(\.)?([0-9]{1})?$", $data->point, $matches)) {
    	print_r($matches);
        throw new Exception('Enter only numbers and periods (.)');
    }
}

print_r($data->point);die();



// Giá trị mặc định bằng 1
// Hệ thống hiển thị input box để chỉnh sửa
// - required
// - maxlength=6
// - Chỉ nhập chữ số và dấu chấm (.)
// - Tối đa 4 chữ số sau dấu chấm
// - Tự cắt số 0 đầu/ cuối(sau dấu chấm) sau khi lưu
// - trim() khoảng trắng
// Nút xác nhận chỉ Lưu khi có chỉnh sửa