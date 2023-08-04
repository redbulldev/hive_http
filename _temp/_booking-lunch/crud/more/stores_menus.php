<?php

use Illuminate\Database\Capsule\Manager as DB;


$obj->leftJoin('stores', function ($join) {
    $join->on('stores.id', '=', 'stores_menus.store_id');
    $join->on('stores.isdelete', '=', DB::raw('0'));
});
$obj->leftJoin('menus', function ($join) {
    $join->on('menus.id', '=', 'stores_menus.menu_id');
    $join->on('menus.isdelete', '=', DB::raw('0'));
});
$moreselect = ['stores.title AS store_title', 'menus.title AS menu_title'];
