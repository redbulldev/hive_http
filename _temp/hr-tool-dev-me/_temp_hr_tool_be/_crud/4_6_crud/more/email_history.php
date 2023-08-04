<?php
use Illuminate\Database\Capsule\Manager as DB;
$obj->leftJoin('cv', 'cv.id', '=', 'email_history.cv_id')->leftJoin('email', 'email.id', '=', 'email_history.email_id');

$moreselect= ['cv.fullname AS fullname', 'email.title AS email_title'];
