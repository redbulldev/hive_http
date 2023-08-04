<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

// throwError($container, $request, [
//     'office_id' => v::digit()->notEmpty(),
//     'store_id' => v::digit()->notEmpty(),
//     'menu_id' => v::digit()->notEmpty(),
//     'price' => v::digit()->notEmpty(),
// ]);
if (!empty($data->office_id)) {
    if (!DB::table('offices')->where('id', $data->office_id)->where('isdelete', 0)->count()) {
        throw new Exception('Văn phòng '. $data->office_id.' không tồn tại');
    }
}
if (!empty($data->store_id)) {
    if (!DB::table('stores')->where('id', $data->store_id)->where('isdelete', 0)->count()) {
        throw new Exception('Quán ăn ' . $data->store_id . 'không tồn tại');
    }
}
if (!empty($data->menu_id)) {
    if (!DB::table('menus')->where('id', $data->menu_id)->where('isdelete', 0)->count()) {
        throw new Exception('Món ăn ' . $data->menu_id . ' không tồn tại');
    }
}