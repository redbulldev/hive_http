<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request, [
    'title' => v::length(3, 200)->notEmpty(),
    'code' => v::length(3, 10)->notEmpty(),
    'starttime' => v::notEmpty(),
    'endtime' => v::notEmpty(),
]);

if (!empty($data->title)) {
    $where = ['title' => trim($data->title)];
    if (DB::table('offices')->where($where)->where('isdelete', 0)->count()) {
        throw new Exception('Tên văn phòng đã tồn tại');
    }
}

if (!empty($data->code)) {
    $where = ['code' => trim($data->code)];
    if (DB::table('offices')->where($where)->where('isdelete', 0)->count()) {
        throw new Exception('Ký hiệu văn phòng đã tồn tại');
    }
}