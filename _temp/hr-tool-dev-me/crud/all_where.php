<?php
// require './shared/where.php';
if (file_exists(__DIR__ . '/more/' . $file . '.php'))
    require(__DIR__ . '/more/' . $file . '.php');

// die($file); //dashboard

if (isset($params['orderby'])) {
    // die('ok');
    $arrmtob = explode('__', $params['orderby']);
    foreach ($arrmtob as $ob) {
        $arrob = explode('-', $ob);
        if (count($arrob) == 2){
            //$obj->orderBy($name . '.' . $arrob[0], $arrob[1]);
            $obj->orderBy($arrob[0], $arrob[1]);
        }
    }
} else if (isset($orderby)) {
    // die('ok');

    foreach ($orderby as $ob) {
        $obj->orderBy($ob['name'], $ob['type']);
    }
}else {
    // die('ok'); //true

    $obj->orderBy($name . '.'.$columnorb, 'DESC');
}

// v1/cv?assignee_id=chilk&daterange=1669827600-1673283599&limit=10&page=1
if ($key != '') {
    // die($key); // => ''

    if(empty($listKeySearch))
        $obj = findby($name, $obj, $key);
    else{
        $obj = findby($name, $obj, $key, $listKeySearch);
    }
}

// die('ok');
            // die($daterange);

if ($daterange != '') {
    $arrdate = explode('-', $daterange);
    // print_r($arrdate); //['1669827600','1670605199']
    // die($arrdate);


    if (count($arrdate) == 2) {
        $begin = $arrdate[0];
        $end = $arrdate[1];
        if ($begin > 0 && $end > 0) {
    // die('ok1');

            $obj->where($name . '.datecreate', '>=', $begin)->where($name . '.datecreate', '<=', $end);
        }
    }
    
}