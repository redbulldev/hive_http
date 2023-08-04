<?php

use Illuminate\Database\Capsule\Manager as DB;
//Reset món ăn mặc định ở bảng offices_menus
if(!empty($id))
{
    $old = DB::table('offices_menus')->where(['id' => $id])->first();
    if($old) {
        DB::table('offices')->where('id', $old->office_id)->update(['store_id' => null, 'menu_id' => null]);
    }
}
if (!empty($data) && is_array($data) && count($data) > 0) {
    foreach($data as $id)
    {
        $old = DB::table('offices_menus')->where(['id' => $id])->first();
        if ($old) {
            DB::table('offices')->where('id', $old->office_id)->update(['store_id' => null, 'menu_id' => null]);
        }
    }
}
