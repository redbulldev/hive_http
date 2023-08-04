<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($id)) {
    $history = DB::table('history')->where(['id' => $id])->first();
    if ($history) {
        $allhis = DB::table('history')->where(['username' => $history->username])->limit(5)->orderBy('date', 'DESC')->get()->toArray();
        $historyData = ['history' => json_encode($allhis)];
        DB::table('users')->where(['username' => $history->username])->update($historyData);
    }
}
//Kiểm tra Xem số lượng đã trên 10 chưa
if (!empty($store) && !empty($offmenu)) {
    $number = DB::table('history')->where(['booked' => 1, 'isdelete' => 0, 'date' => date('Y-m-d'), 'store_id' => $store->id])->count();
    //Lấy danh sách món ăn
    $listoffmenu = DB::table('offices_menus')->where(['store_id' => $store->id, 'office_id' => $offmenu->office_id])->get();
    foreach ($listoffmenu as $item) {
        $update=['price'=> $item->price];
        if ($number >= 10 && $item->priceten > 0) {
            $update = ['price' => $item->priceten];
        }
        DB::table('history')->where(['booked' => 1, 'isdelete' => 0, 'date' => date('Y-m-d'), 'store_id' => $store->id, 'menu_id' => $item->menu_id])
            ->update($update);
    }
}

if(!empty($olddata))
{
    $oldstore = DB::table('stores')->where(['id' => $olddata->store_id, 'isdelete' => 0, 'status' => 1])->first();
    $oldoffmenu = DB::table('offices_menus')->where(['menu_id' => $olddata->menu_id, 'office_id' => $olddata->office_id])->first();
    //Kiểm tra Xem số lượng đã trên 10 chưa
    if (!empty($oldstore) && !empty($oldoffmenu)) {
        $number = DB::table('history')->where(['booked' => 1, 'isdelete' => 0, 'date' => date('Y-m-d'), 'store_id' => $oldstore->id])->count();
        //Lấy danh sách món ăn
        $listoffmenu = DB::table('offices_menus')->where(['store_id' => $oldstore->id, 'office_id' => $oldoffmenu->office_id])->get();
        foreach ($listoffmenu as $item) {
            $update = ['price' => $item->price];
            if ($number >= 10 && $item->priceten > 0) {
                $update = ['price' => $item->priceten];
            }
            DB::table('history')->where(['booked' => 1, 'isdelete' => 0, 'date' => date('Y-m-d'), 'store_id' => $oldstore->id, 'menu_id' => $item->menu_id])
                ->update($update);
        }
    }
}