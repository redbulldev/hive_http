<?php

use Illuminate\Database\Capsule\Manager as DB;

//Lấy danh sách các store liên kết với thực đơn này
$liststore = DB::table('stores_menus')->where('menu_id', $id)->get();
//Xóa dữ liệu bảng liên kết với store
DB::table('stores_menus')->where('menu_id', $id)->delete();
//Xóa bảng dữ liệu liên kết với offices
DB::table('offices_menus')->where('menu_id', $id)->delete();
//Reset món ăn mặc định ở bảng offices
DB::table('offices')->where('menu_id', $id)->update(['store_id'=>null, 'menu_id' => null]);
//Update lại bảng store
foreach ($liststore as $item) {
    $menus = DB::table('menus')->join('stores_menus', 'stores_menus.menu_id', 'menus.id')
        ->where('stores_menus.store_id', $item->store_id)
        ->select(['menus.id', 'menus.title'])
        ->get()->toArray();
    DB::table('stores')->where('id', $item->store_id)->update(['menus' => json_encode($menus)]);
}
