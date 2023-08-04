<?php

$obj->leftJoin('offices', 'offices.id', '=', 'users.office_id');
$moreselect = ['offices.title AS office_title', 'offices.code AS office_code'];
