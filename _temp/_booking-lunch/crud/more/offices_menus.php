<?php

$obj->leftJoin('stores', 'stores.id', '=', 'offices_menus.store_id')
    ->leftJoin('offices', 'offices.id', '=', 'offices_menus.office_id')
    ->leftJoin('menus', 'menus.id', '=', 'offices_menus.menu_id');
$moreselect = ['stores.title AS store_title','offices.title AS office_title', 'offices.code AS office_code', 'menus.title AS menu_title'];
