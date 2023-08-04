<?php

use Illuminate\Database\Capsule\Manager as DB;


if (!empty($data->title)) {
    $where = ['title' => trim($data->title)];
    if (DB::table('offices')->where($where)->where('isdelete', 0)->where('id', '!=', $id)->count()) {
        throw new Exception('Tên văn phòng đã tồn tại');
    }
}

if (!empty($data->code)) {
    $where = ['code' => trim($data->code)];
    if (DB::table('offices')->where($where)->where('isdelete', 0)->where('id', '!=', $id)->count()) {
        throw new Exception('Ký hiệu văn phòng đã tồn tại');
    }
}