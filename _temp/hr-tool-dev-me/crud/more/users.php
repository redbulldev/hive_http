<?php
$obj->leftJoin('role', 'role.id', '=', 'users.role_id');
$moreselect= ['role.title AS role_title'];
