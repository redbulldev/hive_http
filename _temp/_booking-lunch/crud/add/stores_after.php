<?php

use Illuminate\Database\Capsule\Manager as DB;

if (isset($data->menus) && is_array($data->menus) && count($data->menus) > 0) {
    $menus = [];
    foreach ($data->menus as $menu) {
        if (!empty($menu->id) && !empty($menu->id)) {
            $pradd = ['menu_id' => $menu->id, 'store_id' => $id];
            $lastId = DB::table('stores_menus')->insertGetId($pradd);
            if ($lastId > 0) {
                $menus[] = $menu;
            }
        }
    }
    if (count($menus) < count($data->menus)) {
        DB::table('stores')->where('id', $id)->update(['menus' => json_encode($menus)]);
    }
}