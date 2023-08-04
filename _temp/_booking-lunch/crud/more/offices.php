<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->leftJoin('stores', 'stores.id', '=', 'offices.store_id')
->leftJoin('menus', 'menus.id', '=', 'offices.menu_id');
$moreselect = ['stores.title AS store_title', 'menus.title AS menu_title'];

if (!empty($params['nomenu'])) {
    $obj->whereNotExists(function($query)
    {
        $query->select(DB::raw(1))->from('offices_menus')->whereRaw('offices_menus.office_id = offices.id');
    });
}