<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->booked)) {
    if (!empty($data->menu_id) && !empty($data->office_id)) {
        $menu = DB::table('menus')->where(['id' => $data->menu_id, 'isdelete' => 0, 'status' => 1])->first();
        if ($menu) {
            $data->menu = $menu->title;
            $offmenu = DB::table('offices_menus')->where(['menu_id' => $data->menu_id, 'office_id' => $data->office_id])->first();
            if ($offmenu) {
                //Kiểm tra Xem số lượng đã phù hợp chưa
                $store = DB::table('stores')->where(['id' => $offmenu->store_id, 'isdelete' => 0, 'status' => 1])->first();
                if ($store) {
                    $checknumber = true;
                    if ($store->max > 0) {
                        //Có kiểm tra số lượng
                        $number = DB::table('history')->where(['booked' => 1, 'isdelete' => 0, 'date' => date('Y-m-d'), 'store_id' => $offmenu->store_id])->count();
                        if ($number > $store->max - 1) {
                            $checknumber = false;
                            throw new Exception('Bạn không thể chọn món do số lượng vượt giới hạn');
                        }
                    }
                    if ($checknumber == true) {
                        $data->store_id = $offmenu->store_id;
                        $data->price = $offmenu->price;
                    }
                } else {
                    throw new Exception('Quán ăn không tồn tại');
                }
            } else {
                throw new Exception('Món ăn không được cung cấp');
            }
        } else {
            throw new Exception('Món ăn không tồn tại');
        }
    } else {
        throw new Exception('Cần xác định ID món ăn và ID văn phòng');
    }
} else {
    $data->store_id = null;
    $data->price = null;
    $data->menu_id = null;
    $data->menu = null;
}
