<?php

use Illuminate\Database\Capsule\Manager as DB;
$disableLimit = true;
if (!empty($params['store_id'])) {
    $store = explode('-', $params['store_id']);
}
$objstore= DB::table('history')->select('store_id')->where(['date' => date('Y-m-d'), 'booked' => 1])->where('store_id', '>', 0)->groupBy('store_id');
if (!empty($store) && count($store)) {
    $objstore->whereIn('store_id', $store);
}
$liststore = $objstore->get()->toArray();
foreach ($liststore as $indexstore=>$store) {
    $liststore[$indexstore] = DB::table('stores')->select('id', 'title', 'address')->where('id', $store->store_id)->first();
    $listmenu = DB::table('history')->select('menu_id')->where(['date' => date('Y-m-d'), 'booked' => 1, 'store_id'=> $store->store_id])->groupBy('menu_id')->get()->toArray();
    foreach ($listmenu as $indexmenu => $menu) {
        $listmenu[$indexmenu]=DB::table('menus')->select('id', 'title')->where('id', $menu->menu_id)->first();
        //Văn phòng
        $listoffice = DB::table('history')->select('office_id')->where(['date' => date('Y-m-d'), 'booked' => 1, 'store_id' => $store->store_id, 'menu_id' => $menu->menu_id])->groupBy('office_id')->get()->toArray();
        foreach ($listoffice as $indexoffice => $office) {
            $listoffice[$indexoffice] = DB::table('offices')->select('id', 'title', 'code')->where('id', $office->office_id)->first();
            $listoffice[$indexoffice]->count = DB::table('history')->where(['date' => date('Y-m-d'), 'booked' => 1, 'store_id' => $store->store_id, 'menu_id' => $menu->menu_id, 'office_id' => $office->office_id])->count();
            $listoffice[$indexoffice]->notes= DB::table('history')->where(['date' => date('Y-m-d'), 'booked' => 1, 'store_id' => $store->store_id, 'menu_id' => $menu->menu_id, 'office_id' => $office->office_id])->where('notes','!=','')->select('username', 'notes')->get()->toArray();
        }
        $listmenu[$indexmenu]->offices = $listoffice;
        //Người dùng
        $listuser = [];
        $listhistory = DB::table('history')->select('username')->where(['date' => date('Y-m-d'), 'booked' => 1, 'store_id' => $store->store_id, 'menu_id'=> $menu->menu_id])->groupBy('username')->get()->toArray();
        foreach ($listhistory as $indexuser => $user) {
            $user = DB::table('users')->join('offices', 'offices.id', 'users.office_id')->select('users.username', 'users.fullname', 'users.notes', 'offices.title', 'offices.code')->where('users.username', $user->username)->first();
            if($user) $listuser[] =  $user;
        }
        $listmenu[$indexmenu]->users= $listuser;
    }
    $liststore[$indexstore]->menus = $listmenu;
}
$moreresults['data'] = $liststore;//DB::table('history')->where(['date' => date('Y-m-d')])->orderBy('store_id','DESC')->orderBy('menu_id', 'ASC')->get()->toArray();

$moreresults['total'] = count($liststore);