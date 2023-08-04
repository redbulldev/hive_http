<?php

use Illuminate\Database\Capsule\Manager as DB;

//Xóa bảng dữ liệu liên kết với offices
DB::table('offices_menus')->where('office_id', $id)->delete();
