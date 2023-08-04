<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->title)) {
    $where = ['title' => trim($data->title)];
    if (DB::table('stores')->where($where)->where('isdelete', 0)->where('id','!=', $id)->count()) {
        throw new Exception('Quán ăn đã tồn tại');
    }
}

if (!empty($data->menus) && is_array($data->menus)) {
    foreach ($data->menus as $menu) {
        if (empty($menu->id)) throw new Exception('ID Món ăn không tồn tại');
        if (!DB::table('menus')->where('id', $menu->id)->where('isdelete', 0)->count()) {
            throw new Exception('Món ăn không tồn tại');
        }
    }
} else {
    throw new Exception('Dữ liệu món ăn phải là dạng danh sách');
}
