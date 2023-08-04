<?php

use Illuminate\Database\Capsule\Manager as DB;

if (isset($data->menus) && is_array($data->menus) && count($data->menus) > 0) {
    DB::table('stores_menus')->where(['store_id' => $id])->delete();
    $menuOffice=[];
    $menus = [];
    foreach ($data->menus as $menu) {
        if (!empty($menu->id) && !empty($menu->id)) {
            $pradd = ['menu_id' => $menu->id, 'store_id' => $id];
            $lastId = DB::table('stores_menus')->insertGetId($pradd);
            if ($lastId > 0) {
                $menus[] = $menu;
            }
            $arr= DB::table('offices_menus')->where($pradd)->get()->toArray();
            if(count($arr)>0)
            {
                $menuOffice = array_merge($menuOffice, $arr);
            }
        }
    }
    if (count($menus) < count($data->menus)) {
        DB::table('stores')->where('id', $id)->update(['menus' => json_encode($menus)]);
    }
    DB::table('offices_menus')->where(['store_id' => $id])->delete();
    foreach ($menuOffice as $mn) {
        DB::table('offices_menus')->insert([
            'store_id'=>$mn->store_id,
            'menu_id' => $mn->menu_id,
            'office_id' => $mn->office_id,
            'price' => $mn->price,
            'isdefault' => $mn->isdefault,
        ]);
    }
}