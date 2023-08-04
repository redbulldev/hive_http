<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj = DB::table($name)->where('offices.id', $id);
$obj->leftJoin('stores', 'stores.id', '=', 'offices.store_id');
$obj->leftJoin('menus', 'menus.id', '=', 'offices.menu_id');
$one = $obj->select('offices.*', 'stores.title AS store_title', 'menus.title AS menu_title')->first();
