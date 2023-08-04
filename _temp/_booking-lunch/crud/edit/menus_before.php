<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->title)) {
    $where = ['title' => trim($data->title)];
    if (DB::table('menus')->where($where)->where('isdelete', 0)->where('id','!=', $id)->count()) {
        throw new Exception('Món ăn đã tồn tại');
    }
}

