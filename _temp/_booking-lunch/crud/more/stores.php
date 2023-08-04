<?php


if (!empty($params['menu_title'])) {
    $menus = explode('-', $params['menu_title']);
    $obj->where(function ($query) use ($menus) {
        foreach ($menus as $id) {
            $k1 = '"' . trim($id) . '"';
            $query->orWhere('stores.menus', 'LIKE', "%$k1%");
        }
    });
}