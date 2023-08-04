<?php
if (!empty($params['access'])) {
    $obj->join('vmenus', 'vmenus.menu_id', '=', 'menus.id');
}