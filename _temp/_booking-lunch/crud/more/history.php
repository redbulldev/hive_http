<?php

$obj->leftJoin('stores', 'stores.id', '=', 'history.store_id')->leftJoin('offices', 'offices.id', '=', 'history.office_id');
$moreselect = ['stores.title AS store_title','offices.title AS office_title', 'offices.code AS office_code'];
