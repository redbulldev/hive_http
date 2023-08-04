<?php

use Illuminate\Database\Capsule\Manager as DB;

//Xóa bảng dữ liệu liên kết với offices
DB::table('offices_menus')->where('store_id', $id)->delete();
//Reset món ăn mặc định ở bảng offices
DB::table('offices')->where('store_id', $id)->update(['store_id' => null, 'menu_id' => null]);