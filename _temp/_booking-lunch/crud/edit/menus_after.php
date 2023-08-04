<?php

use Illuminate\Database\Capsule\Manager as DB;


$liststore = DB::table('stores_menus')->where('menu_id', $id)->get();
foreach ($liststore as $item) {
    $menus = DB::table('menus')->join('stores_menus', 'stores_menus.menu_id', 'menus.id')
        ->where('stores_menus.store_id', $item->store_id)
        ->select(['menus.id', 'menus.title'])
        ->get()->toArray();
    DB::table('stores')->where('id', $item->store_id)->update(['menus' => json_encode($menus)]);
}
